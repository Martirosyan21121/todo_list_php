<?php

use controller\TaskController;
use model\User;
use controller\UserController;
use thecodeholic\phpmvc\Application;

require_once 'vendor/autoload.php';
require_once 'model/User.php';
require_once 'controller/UserController.php';
require_once 'controller/TaskController.php';

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
$app->router->get('/adminPage', [UserController::class, 'adminSinglePage']);
$app->router->post('/login', [UserController::class, 'login']);
$app->router->get('/user/update/{id}', [UserController::class, 'showUpdateForm']);
$app->router->post('/user/update/', [UserController::class, 'updateUser']);

$app->router->get('/allTasks/{id}', [TaskController::class, 'allUserTasks']);
$app->router->get('/allTasks/addTask/{id}', [TaskController::class, 'addTask']);
$app->router->get('/allTasks/deleteTask/{id}', [TaskController::class, 'deleteTask']);
$app->router->get('/allTasks/update/{id}', [TaskController::class, 'showTaskUpdateForm']);
$app->router->post('/allTasks/addTask/saveTask/{id}', [TaskController::class, 'saveTask']);

$app->router->get('/logout', [UserController::class, 'logout']);

$app->run();