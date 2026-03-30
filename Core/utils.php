<?php

use Core\Session;

function pprint($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function getBaseUrl()
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    return rtrim(dirname($scriptName), '/');
}

function appendControllerPath($path)
{
    return 'Controller\\' . $path;
}

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Store the current URL as the previous URL before redirecting to login
 * This allows users to be redirected back after successful login
 */
function storePreviousUrl()
{
    Session::start();
    // Store the full request URI (including query string)
    $previousUrl = $_SERVER['REQUEST_URI'] ?? '/';
    // $_SESSION['previous_url'] = $previousUrl;
    // Don't store login/register pages as previous URL
    if (strpos($previousUrl, '/login') === false && strpos($previousUrl, '/register') === false) {
        $_SESSION['previous_url'] = $previousUrl;
    }
}

/**
 * Get and clear the stored previous URL
 * Returns the URL if it exists, otherwise returns null
 */
function getPreviousUrl()
{
    Session::start();
    $url = $_SESSION['previous_url'] ?? NULL;
    if ($url) {
        unset($_SESSION['previous_url']);
    }
    return $url;
}

/**
 * Clear the stored previous URL without returning it
 */
function clearPreviousUrl()
{
    Session::start();
    unset($_SESSION['previous_url']);
}


/**
 * Resize and optimize image
 * @param string $sourcePath Path to source image
 * @param string $targetPath Path to save resized image
 * @param int $maxWidth Maximum width (default: 1920)
 * @param int $maxHeight Maximum height (default: 1080)
 * @param int $quality JPEG quality (1-100, default: 85)
 * @return bool Success status
 */
function resizeImage($sourcePath, $targetPath, $maxWidth = 1920, $maxHeight = 1080, $quality = 85)
{
    // Check if GD extension is available
    if (!extension_loaded('gd')) {
        error_log('GD extension is not loaded. Please enable GD extension in PHP.');
        // Fallback: just copy the file if GD is not available
        if (file_exists($sourcePath)) {
            return copy($sourcePath, $targetPath);
        }
        return false;
    }

    if (!file_exists($sourcePath)) {
        return false;
    }

    // Get image info
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) {
        return false;
    }

    $originalWidth = $imageInfo[0];
    $originalHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // Calculate new dimensions - maintain aspect ratio
    // IMPORTANT: Only downscale, NEVER upscale (to prevent blurry images)
    $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);

    // If image is smaller than max dimensions, keep original size (no upscaling)
    if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
    } else {
        // Only downscale if image is larger than max dimensions
        $newWidth = (int) ($originalWidth * $ratio);
        $newHeight = (int) ($originalHeight * $ratio);
    }

    // Ensure minimum dimensions (don't make too small)
    if ($newWidth < 100)
        $newWidth = 100;
    if ($newHeight < 100)
        $newHeight = 100;

    // Create image resource from source
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    if ($sourceImage === false) {
        return false;
    }

    // Create new image with true color for better quality
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG and GIF
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
    } else {
        // For JPEG, use white background
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);
    }

    // Enable high-quality resampling
    // imagecopyresampled uses bilinear interpolation which is good quality
    // For even better quality on downscaling, we could use imagecopyresampled with smoothing
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Save resized image
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($newImage, $targetPath, $quality);
            break;
        case 'image/png':
            // PNG quality is 0-9, convert from 0-100
            $pngQuality = (int) (9 - ($quality / 100) * 9);
            $result = imagepng($newImage, $targetPath, $pngQuality);
            break;
        case 'image/gif':
            $result = imagegif($newImage, $targetPath);
            break;
        case 'image/webp':
            $result = imagewebp($newImage, $targetPath, $quality);
            break;
    }

    // Free memory
    imagedestroy($sourceImage);
    imagedestroy($newImage);

    return $result;
}

// TODO: Add CRSF field generation and validation functions for better security in forms
function crsf_field()
{
    return;
}
?>