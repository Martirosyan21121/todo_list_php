<?php

namespace controller;

use thecodeholic\phpmvc\Controller;
use model\Todo;
require_once 'model\Todo.php';
class TaskController extends Controller
{
    public function allUserTasks($id)
    {

        $taskModel = new Todo();

        $tasks = $taskModel->getAllByUserId($id);

        return $this->render('allTasks', ['tasks' => $tasks]);
    }
}