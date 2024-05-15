<?php

use controller\UserController;
use router\Router;
use thecodeholic\phpmvc\Application;
use database\DBConnection;

require_once 'vendor/autoload.php';
require_once 'router/Router.php';
require_once 'database/DBConnection.php';
require_once 'controller/UserController.php';


$dbConnection = new DBConnection();
$dbParams = [
    'host' => $dbConnection->getHost(),
    'username' => $dbConnection->getUsername(),
    'password' => $dbConnection->getPassword(),
    'database' => $dbConnection->getDatabase()
];
$app = new Application(__DIR__, $dbParams);

$router = new Router();
$router->get('/', [UserController::class, 'login']);
$router->run();
