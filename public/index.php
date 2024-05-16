<?php

use model\User;
use router\Router;
use controller\UserController;
use database\DBConnection;
use thecodeholic\phpmvc\Application;

require_once '../vendor/autoload.php';
require_once '../model/User.php';
require_once '../controller/UserController.php';
require_once '../database/DBConnection.php';
require_once '../router/Router.php';

$router = new Router();

$dbConnection = new DBConnection();

$config = [
    'userClass' => User::class,
    "db" => [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=todo',
        'user' => 'root',
        'password' => ''
    ],
];

$app = new Application(__DIR__, $config);

$router->get('/', function () use ($app) {
    echo 'Home';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$app->run();