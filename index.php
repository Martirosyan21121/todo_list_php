<?php

use model\User;
use controller\UserController;
use thecodeholic\phpmvc\Application;


require_once 'vendor/autoload.php';
require_once 'model/User.php';
require_once 'controller/UserController.php';

$error_condition = false;

try {
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

    if ($error_condition) {
        throw new Exception("Simulated error condition");
    }

    $app->run();
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error_message'] = $e->getMessage();
    header("Location: views/_error.php");
    exit;
}
