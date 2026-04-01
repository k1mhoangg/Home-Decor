<?php
// Load .env into environment variables (simple parser)
$envFile = BASE_PATH . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0)
            continue;
        if (strpos($line, '=') === false)
            continue;
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key === '')
            continue;
        // strip surrounding quotes
        if (
            (substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
        ) {
            $value = substr($value, 1, -1);
        }
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Dynamic PSR-4 autoloader cho Core, Controller, Model
spl_autoload_register(function (string $class) {
    // Định nghĩa các namespace mappings
    $namespaces = [
        'Core\\' => BASE_PATH . 'Core/',
        'Controller\\' => BASE_PATH . 'Controller/',
        'Model\\' => BASE_PATH . 'Model/',
        'Middleware\\' => BASE_PATH . 'Core/Middleware/',
    ];

    // Duyệt qua từng namespace để tìm match
    foreach ($namespaces as $prefix => $base_dir) {
        $len = strlen($prefix);

        // Kiểm tra class có bắt đầu với prefix không
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        // Lấy phần relative class name
        $relative_class = substr($class, $len);

        // Build file path
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // Nếu file tồn tại thì require
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});