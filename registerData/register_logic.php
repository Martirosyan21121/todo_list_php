<?php

use model\User;
use model\UserPic;

require_once '../model/User.php';
require_once '../model/UserPic.php';

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    $username = $_POST ['username'];
    $email = $_POST ['email'];
    $password = $_POST ['password'];

    $user = new User();
    $userPic = new UserPic();

    if (strlen($username) < 5) {
        header("Location: ../views/register.php?error=min_length");
        exit;
    }

    if ($user->emailExists($email)) {
        header("Location: ../views/register.php?error=email_exist");
        exit;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../views/register.php?error=invalid_email");
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
        header("Location: ../views/register.php?error=password_pattern");
        exit;
    }


    session_start();
    $registered = $user->register($username, $email, $password);
    if ($registered) {
        $userData = $user->getUserDataByEmail($email);
        $user->userData($userData);
        exit;
    } else {
        echo "Registration failed.";
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}

