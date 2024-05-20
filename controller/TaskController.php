<?php

namespace controller;

use thecodeholic\phpmvc\Controller;
use model\Todo;
require_once 'model\Todo.php';
class TaskController extends Controller
{
    public function allUserTasks()
    {
        return $this->render('allTasks');
    }
}