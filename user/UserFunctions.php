<?php
namespace user;
session_start();
class UserFunctions
{
    function updateUser($user)
    {
        $_SESSION['user_data'] = $user;
        header('Location: ../view/updateUser.php');
        exit();
    }

    function updateAdmin($user)
    {
        $_SESSION['admin_data'] = $user;
        header('Location: ../view/updateAdmin.php');
        exit();
    }

}
