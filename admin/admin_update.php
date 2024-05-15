<?php

use model\User;
use user\UserFunctions;

require_once '../model/User.php';
require_once '../user/UserFunctions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $userFun = new UserFunctions();

    if (isset($_POST['update_admin'])) {
        $email = $_POST['email'];
        $userData = $user->getUserDataByEmail($email);
        $userFun->updateAdmin($userData);
        exit();
    }
}