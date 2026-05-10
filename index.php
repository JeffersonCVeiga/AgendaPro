<?php

session_start();

define('BASE_PATH', __DIR__);
define('BASE_URL',  'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/models/UserModel.php';
require_once BASE_PATH . '/app/models/ActivityModel.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/ActivityController.php';
require_once BASE_PATH . '/app/controllers/CalendarController.php';

$controller = $_GET['c'] ?? 'auth';
$action     = $_GET['a'] ?? 'index';

$routes = [
    'auth'     => AuthController::class,
    'activity' => ActivityController::class,
    'calendar' => CalendarController::class,
];

if (!array_key_exists($controller, $routes)) {
    http_response_code(404);
    exit('Página não encontrada.');
}

$class = $routes[$controller];
$obj   = new $class();

if (!method_exists($obj, $action)) {
    http_response_code(404);
    exit('Ação não encontrada.');
}

$obj->$action();
