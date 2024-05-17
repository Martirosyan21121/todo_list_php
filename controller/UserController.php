<?php

namespace controller;
use thecodeholic\phpmvc\Controller;

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
