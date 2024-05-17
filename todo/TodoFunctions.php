<?php
namespace todo;
use model\Todo;

class TodoFunctions
{
    function reloadTodoList()
    {
        $todo = new Todo();
        $userId = $_SESSION['user']['id'];
        $show = $todo->getAllByUserId($userId);
        $_SESSION['allTasks'] = $show;
        header('Location: ../views/allTasks.php');
        exit();
    }

    function updateTask($task)
    {
        $_SESSION['task'] = $task;
        header('Location: ../views/update_task.php');
        exit();
    }

    function handleError($errorType)
    {
        header("Location: ../views/addTask.php?error=$errorType");
        exit();
    }
}