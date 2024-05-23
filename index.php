<?php

use controller\AdminController;
use controller\TaskController;
use model\User;
use controller\UserController;
use thecodeholic\phpmvc\Application;

require_once 'vendor/autoload.php';
require_once 'model/User.php';
require_once 'controller/UserController.php';
require_once 'controller/TaskController.php';
require_once 'controller/AdminController.php';

$config = [
    'userClass' => User::class,
    "db" => [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=todo',
        'user' => 'root',
        'password' => ''
    ],
];

$app = new Application(__DIR__, $config);
// user
$app->router->get('/', [UserController::class, 'home']);
$app->router->get('/register', [UserController::class, 'register']);
$app->router->get('/singlePage/{id}', [UserController::class, 'singlePage']);
$app->router->get('/user/update/{id}', [UserController::class, 'showUpdateForm']);
$app->router->post('/user/update/{id}', [UserController::class, 'updateUser']);
$app->router->post('/register/user', [UserController::class, 'registerUser']);
$app->router->post('/login', [UserController::class, 'login']);
// task
$app->router->get('/allTasks/{id}', [TaskController::class, 'allUserTasks']);
$app->router->get('/allTasks/addTask/{id}', [TaskController::class, 'addTask']);
$app->router->get('/allTasks/deleteTask/{id}', [TaskController::class, 'deleteTask']);
$app->router->get('/allTasks/update/{id}', [TaskController::class, 'showTaskUpdateForm']);
$app->router->get('/allTasks/status/{id}', [TaskController::class, 'taskStatus']);
$app->router->post('/allTasks/update/updateTask/{id}', [TaskController::class, 'updateTask']);
$app->router->post('/allTasks/addTask/saveTask/{id}', [TaskController::class, 'saveTask']);
//admin
$app->router->get('/adminPage/{id}', [AdminController::class, 'adminSinglePage']);
$app->router->get('/admin/update/{id}', [AdminController::class, 'showAdminUpdateForm']);
$app->router->get('/admin/showAllUsers', [AdminController::class, 'showAllUsers']);
$app->router->get('/admin/showAllUsers/delete/{id}', [AdminController::class, 'deleteUser']);

$app->router->post('/admin/updateData/{id}', [AdminController::class, 'updateAdmin']);
$app->router->post('', [AdminController::class, '']);
$app->router->post('', [AdminController::class, '']);

$app->router->get('/logout', [UserController::class, 'logout']);

$app->run();