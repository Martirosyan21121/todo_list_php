<?php

namespace controller;

use model\Admin;
use model\TaskFile;
use model\Todo;
use model\User;
use model\UserPic;

require_once "../model/Admin.php";
require_once "../model/User.php";
require_once "../model/Todo.php";
require_once "../model/UserPic.php";
require_once "../model/TaskFile.php";

class AdminController
{
    public function allUsersData(): array
    {
        $admin = new Admin();
        return $admin->getAllUserData();
    }

    public static function deleteUserById($userId): bool
    {
        $admin = new Admin();
        return $admin->deleteUserById($userId);
    }
}

if (isset($_GET['delId'])) {
    $user = new User();
    $todo = new Todo();
    $userPic = new UserPic();
    $taskFile = new TaskFile();
    $userData = $user->getUserDataById($_GET['delId']);
    $userPicId = $userData['files_id'];

    if ($userPicId !== null) {
        $fileToUpdate = $userPic->findFileById($userData['files_id']);
        $fileToUpdateName = $fileToUpdate['files_name'];
        $filePathToUpdate = '../img/userPic/' . $fileToUpdateName;
        if (file_exists($filePathToUpdate)) {
            unlink($filePathToUpdate);
        }
        $userPic->deleteFileById($userData['files_id']);
    }
    $allTasks = $todo->getAllByUserId($_GET['delId']);
    foreach ($allTasks as $task) {
        if ($task['task_files_id'] !== null) {
            $fileToDelete = $taskFile->findFileById($task['task_files_id']);
            $fileToDeleteName = $fileToDelete['files_name'];
            $filePathToDelete = '../img/taskFiles/' . $fileToDeleteName;
            if (file_exists($filePathToDelete)) {
                unlink($filePathToDelete);
            }
            $taskFile->deleteFileById($task['task_files_id']);
        }
    }

    $adminController = new AdminController();
    $adminController->deleteUserById($_GET['delId']);

    header("Location: ../view/allUsers.php");
    exit();
}
