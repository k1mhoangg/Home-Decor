<?php
// Bootstrap: session, base path and autoloader must run before any output

const BASE_PATH = __DIR__ . '/../';
require BASE_PATH . 'autoload.php';

\Core\Session::start();


// require optional utility functions
if (file_exists(BASE_PATH . 'Core/utils.php')) {
    require_once BASE_PATH . 'Core/utils.php';
}

$router = new \Core\Router(); // autoloader should handle this
require_once BASE_PATH . 'routes-register.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
} catch (Error $e) {
    error_log('Router instance is not available: ' . $e->getMessage());
}


?>