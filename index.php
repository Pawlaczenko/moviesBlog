<?php

session_start();

//Ładowanie pliku konfiguracyjnego
require_once 'config.php';

//Ładowanie narzędzi specjalnych
require_once APP_PATH . 'tools.php';

require_once CONTROLLER_PATH . 'controller.php';

$url = $_SERVER['REQUEST_URI'];
$explode_url = explode('/', $url);
$explode_url = array_slice($explode_url, 3);

if (count($explode_url) > 1) {
    $controller = $explode_url[0];
    $action = $explode_url[1];

    $params = array_slice($explode_url, 2);

    require_once CONTROLLER_PATH . $controller . '.php';

    $controllerName = $controller . 'Controller';

    $obj = new $controllerName();
    
    @call_user_func_array([$obj, $action], $params);
} else {
    require_once CONTROLLER_PATH . 'home.php';
    $obj = new homeController();
    $obj->show(0);
}