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

    public function login()
    {
        // Validate email and password
        $errors = $this->validateLoginForm($_POST);

        if (!empty($errors)) {
            // If there are validation errors, redirect back to the login page with error messages
            $this->redirectWithErrors('/login', $errors);
            return;
        }

        // Proceed with authentication
        // ...
    }


    protected function validateLoginForm($formData)
    {
        $errors = [];

        // Validate email
        if (empty($formData['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        // Validate password
        if (empty($formData['password'])) {
            $errors['password'] = 'Password is required.';
        }

        return $errors;
    }

    protected function redirectWithErrors($url, $errors)
    {
        // Redirect back to the specified URL with error messages as query parameters
        $queryString = http_build_query(['errors' => $errors]);
        header("Location: $url?$queryString");
        exit;
    }
}
