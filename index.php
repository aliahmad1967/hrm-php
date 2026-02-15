<?php
/**
 * Main Entry Point
 */

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Load configuration
$config = require CONFIG_PATH . '/app.php';
define('BASE_URL', $config['url']);

// Set error reporting
if ($config['environment'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set($config['timezone']);

// Set locale
setlocale(LC_ALL, 'ar_SA.utf8', 'ar_SA', 'ar');

// Autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load core classes
require APP_PATH . '/Database.php';
require APP_PATH . '/Auth.php';
require APP_PATH . '/View.php';
require APP_PATH . '/Router.php';

// Load helper functions
require BASE_PATH . '/helpers/functions.php';

// Start session
session_start();

// Initialize router
$router = new Router();

// Define routes
require BASE_PATH . '/routes/web.php';

// Get URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Dispatch route
try {
    $router->dispatch($url);
} catch (Exception $e) {
    $code = $e->getCode();
    // Ensure code is a valid HTTP status code (integer between 100-599)
    if (!is_int($code) || $code < 100 || $code > 599) {
        $code = 500;
    }
    http_response_code($code);
    
    if ($code === 404) {
        $view = new View();
        echo $view->render('errors/404', ['message' => $e->getMessage()]);
    } else {
        if ($config['environment'] === 'development') {
            echo "<h1>Error</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        } else {
            $view = new View();
            echo $view->render('errors/500', ['message' => 'حدث خطأ في النظام']);
        }
    }
}