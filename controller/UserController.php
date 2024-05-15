<?php

namespace controller;
use thecodeholic\phpmvc\Controller;

class UserController extends Controller
{

    public function login()
    {
        return $this->render('login');
    }

}