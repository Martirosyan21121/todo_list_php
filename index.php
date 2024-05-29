<?php

use controller\AdminController;
use controller\TaskController;

use model\User;

use controller\UserController;
use controller\MailerController;
use thecodeholic\phpmvc\Application;

require_once 'vendor/autoload.php';
require_once 'controller/UserController.php';
require_once 'controller/TaskController.php';
require_once 'controller/AdminController.php';
require_once 'controller/MailerController.php';

$config = [
    'userClass' => User::class,
    "db" => [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=todo',
        'user' => 'root',
        'password' => ''
    ],
];


$app = new Application(__DIR__, $config);
$user = Application::$app->session->get('user');
$app->router->get('/', [UserController::class, 'home']);
$app->router->get('/register', [UserController::class, 'register']);
$app->router->get('/deactivate', [UserController::class, 'deactivate']);
$app->router->get('/logout', [UserController::class, 'logout']);
$app->router->post('/login', [UserController::class, 'login']);
$app->router->get('/register/gmailSend/{id}', [MailerController::class, 'mailerForRegister']);
$app->router->post('/register/user', [UserController::class, 'registerUser']);
if ($user && $user['user_type'] === 'USER') {
    $app->router->get('/register/gmailSend/{id}', [MailerController::class, 'mailerForRegister']);
    $app->router->get('/user/update/{id}', [UserController::class, 'showUpdateForm']);
    $app->router->get('/singlePage/{id}', [UserController::class, 'singlePage']);
    $app->router->get('/singlePage', [UserController::class, 'singlePageShow']);
    $app->router->get('/user/deletePic/{id}', [UserController::class, 'deletePic']);
    $app->router->post('/user/update/{id}', [UserController::class, 'updateUser']);
    $app->router->get('/allTasks/addTask/{id}', [TaskController::class, 'addTask']);
    $app->router->get('/allTasks/{id}', [TaskController::class, 'allUserTasks']);
    $app->router->get('/allTasks/deleteTask/{id}', [TaskController::class, 'deleteTask']);
    $app->router->get('/allTasks/update/deleteFile/{id}', [TaskController::class, 'deleteTaskFile']);
    $app->router->get('/allTasks/update/{id}', [TaskController::class, 'showTaskUpdateForm']);
    $app->router->get('/allTasks/status/{id}', [TaskController::class, 'taskStatus']);

    $app->router->post('/allTasks/update/updateTask/{id}', [TaskController::class, 'updateTask']);
    $app->router->post('/allTasks/addTask/saveTask/{id}', [TaskController::class, 'saveTask']);
}else if ($user && $user['user_type'] === 'ADMIN') {
    $app->router->get('/adminPage', [AdminController::class, 'adminSinglePageShow']);
    $app->router->get('/adminPage/{id}', [AdminController::class, 'adminSinglePage']);
    $app->router->get('/user/deleteAdminPic/{id}', [AdminController::class, 'deleteAdminPic']);
    $app->router->get('/admin/update/{id}', [AdminController::class, 'showAdminUpdateForm']);
    $app->router->get('/admin/showAllUsers', [AdminController::class, 'showAllUsers']);
    $app->router->get('/admin/showAllUsers/allDeactivates', [AdminController::class, 'deactivateUsers']);
    $app->router->get('/admin/showAllUsers/deactivate/{id}', [AdminController::class, 'deactivate']);
    $app->router->get('/admin/showAllUsers/allDeactivates/delete/{id}', [AdminController::class, 'deleteUser']);
    $app->router->get('/admin/showAllUsers/allDeactivates/activate/{id}', [AdminController::class, 'activateUser']);
    $app->router->get('/admin/showAllUsers/allTasks/{id}', [AdminController::class, 'allTasks']);
    $app->router->get('/admin/showAllUsers/allTasks/delete/{id}', [AdminController::class, 'deleteTaskByAdmin']);
    $app->router->get('/admin/showAllUsers/allTasks/addTaskPage/{id}', [AdminController::class, 'addTaskPage']);
    $app->router->get('/admin/showAllUsers/edit/{id}', [AdminController::class, 'editUserPage']);
    $app->router->get('/admin/showAllUsers/edit/deletePic/{id}', [AdminController::class, 'deleteUserPicByAdmin']);
    $app->router->get('/admin/showAllUsers/allTasks/updatePage/{id}', [AdminController::class, 'updateTaskPage']);
    $app->router->get('/admin/showAllUsers/allTasks/updatePage/deleteFile/{id}', [AdminController::class, 'deleteFileByAdmin']);

    $app->router->post('/admin/updateData/{id}', [AdminController::class, 'updateAdmin']);
    $app->router->post('/admin/showAllUsers/edit/update/{id}', [AdminController::class, 'updateUserByAdmin']);
    $app->router->post('/admin/showAllUsers/allTasks/updatePage/update/{id}', [AdminController::class, 'updateTask']);
    $app->router->post('/admin/showAllUsers/allTasks/addTask/{id}', [AdminController::class, 'addTask']);
}

$app->run();