<?php

use model\Todo;
use model\User;

require_once("../model/Todo.php");
require_once("../model/User.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $todo = new Todo();
    $user = new User();

    $userEmail = $_POST['email'];
    $userData = $user->getUserDataByEmail($userEmail);
    $_SESSION['userData'] = $userData;

    $userId = $userData['id'];
    $tasks = $todo->getAllByUserId($userId);

    $_SESSION['allTasks'] = $tasks;
    header('Location: ../views/allTasks.php');

}