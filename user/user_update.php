<?php

use model\User;
use user\UserFunctions;

require_once '../model/User.php';
require_once '../user/UserFunctions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $userFun = new UserFunctions();

    if (isset($_POST['update_user'])) {
        $email = $_POST['email'];
        $userData = $user->getUserDataByEmail($email);
        $userFun->updateUser($userData);
        exit();
    }
}