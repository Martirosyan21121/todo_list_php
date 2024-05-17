<?php

use model\User;
use controller\UserController;
use database\DBConnection;
use thecodeholic\phpmvc\Application;

require_once '../vendor/autoload.php';
require_once '../model/User.php';
require_once '../controller/UserController.php';
require_once '../database/DBConnection.php';
include_once __DIR__ . "/../views/_error.php";

$dbConnection = new DBConnection();
$config = [
    'userClass' => User::class,
    "db" => [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=todo',
        'user' => 'root',
        'password' => ''
    ],
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [UserController::class, 'home']);
$app->router->get('/register', [UserController::class, 'register']);

$app->run();