<?php

use model\TaskFile;
use model\Todo;
use todo\TodoFunctions;

require_once '../model/Todo.php';
require_once '../model/TaskFile.php';
require_once '../todo/TodoFunctions.php';


session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $text = $_POST['text'];
    $dateTime = $_POST['dateTime'];
    $todo = new Todo();
    $taskFile = new TaskFile();
    $todoFun = new TodoFunctions();

    if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {

        $task = $todo->findTaskById($id);
        $taskUserId = $task['user_id'];
        $randomNumber = rand(10000, 1000000);
        $file_tmp_name = $_FILES['task_file']['tmp_name'];
        $file_name = $id . $randomNumber . $taskUserId . $_FILES['task_file']['name'];
        $upload_directory = '../img/taskFiles/';

        if (!file_exists($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        $uploaded_image_path = $upload_directory . $file_name;

        if (!move_uploaded_file($file_tmp_name, $uploaded_image_path)) {
            header("Location: ../view/add_task.php?error=file_upload_failed");
            exit;
        }

        $taskFile->saveFile($file_name);
        $file = $taskFile->findFileByName($file_name);
        $fileId = $file['id'];
    } else {
        $fileId = null;
    }

    $todoData = $todo->findTaskById($id);
    $fileToUpdateId = $todoData['task_files_id'];

    $fileToUpdate = $taskFile->findFileById($fileToUpdateId);
    $fileToUpdateName = $fileToUpdate['files_name'];

    if ($fileId === null) {
        $fileToUpdateId = $todoData['task_files_id'];
        if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
            if ($fileToUpdate !== null) {
                $filePathToUpdate = '../img/taskFiles/' . $fileToUpdateName;
                if (file_exists($filePathToUpdate)) {
                    unlink($filePathToUpdate);
                }
            }
        }
        if ($fileToUpdateId !== null) {
            $taskFile->deleteFileById($fileToUpdateId);
        }
    }

    if (!empty($fileId)) {
        if ($fileToUpdateId !== null) {
            $taskFile->deleteFileById($fileToUpdateId);
            $filePathToUpdate = '../img/taskFiles/' . $fileToUpdateName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }
    }

    date_default_timezone_set('Asia/Yerevan');
    $currentDateTime = new DateTime();
    $currentDateTime->modify('+10 minutes');

    try {
        $inputDateTime = new DateTime($dateTime);
    } catch (Exception $e) {
        return;
    }

    if ($inputDateTime < $currentDateTime) {
        header("Location: ../view/update_task.php?error=invalid_dateTime_extension");
        exit;
    }

    $updateResult = $todo->updateText($id, $text, $dateTime, $fileId);
    if ($updateResult) {
        $todoFun->reloadTodoList();
    } else {
        $todoFun->handleError('update_failed');
    }
    header('Location: ../view/addTask.php');
}


