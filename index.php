<?php

use model\User;
use controller\UserController;
use thecodeholic\phpmvc\Application;


require_once 'vendor/autoload.php';
require_once 'model/User.php';
require_once 'controller/UserController.php';

$config = [
    'userClass' => User::class,
    "db" => [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=todo',
        'user' => 'root',
        'password' => ''
    ],
];

$app = new Application(__DIR__, $config);

$app->router->get('/', [UserController::class, 'home']);
$app->router->get('/register', [UserController::class, 'register']);
$app->router->post('/register/user', [UserController::class, 'registerUser']);
$app->router->get('/singlePage', [UserController::class, 'singlePage']);

$app->run();

