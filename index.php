<?php

use controller\UserController;
use thecodeholic\phpmvc\Application;
use database\DBConnection;

require_once 'vendor/autoload.php';
require_once 'database/DBConnection.php';
require_once 'controller/UserController.php';


$dbConnection = new DBConnection();

$dbParams = [
    'dsn' => 'mysql:host=' . $dbConnection->getHost() . ';dbname=' . $dbConnection->getDatabase(),
    'username' => $dbConnection->getUsername(),
    'password' => $dbConnection->getPassword(),
];

$app = new Application(__DIR__, [
    'userClass' => 'User',
    'db' => $dbParams, // Make sure 'db' key is defined with the database configuration
]);

$app->router->get('/', [UserController::class, 'login']);
$app->run();
