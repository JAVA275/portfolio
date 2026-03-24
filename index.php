<?php
session_start();
require_once __DIR__ . '/config/app.php';

spl_autoload_register(function($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = explode('/', $url);

$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$action = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

$controllerFile = "controllers/$controllerName.php";

if (file_exists($controllerFile)) {
    require $controllerFile;
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "Page non trouvée";
    }
} else {
    http_response_code(404);
    echo "Page non trouvée";
}
