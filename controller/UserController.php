<?php

namespace controller;

use model\User;
use thecodeholic\phpmvc\Application;
use thecodeholic\phpmvc\Controller;

require_once 'model\User.php';

class UserController extends Controller
{
    public function home()
    {
        return $this->render('login');
    }

    public function register()
    {
        return $this->render('register');
    }

    public function singlePage()
    {
        return $this->render('singlePage');
    }
    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $registrationResult = $userModel->register($username, $email, $password);
            if ($registrationResult) {
                header('Location: /singlePage');
                exit();
            } else {
                header('Location: /register/user?error=registration_failed');
                exit();
            }
        } else {
            return $this->render('register');
        }
    }
}
