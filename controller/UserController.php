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
            $userModel = new User();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = [];

            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 4 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
                $errors['password_length'] = "Password need to be least 8 characters must be 
                used letters(uppercase and lowercase), numbers and symbols. ";
            }

            if ($userModel->emailExists($email)){
                $errors['email_exists'] = "Email already exists.";
            }

            if (!empty($errors)) {
                return $this->render('register', ['errors' => $errors]);
            }


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
