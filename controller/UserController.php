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
}
