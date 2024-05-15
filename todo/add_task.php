<?php

use model\TaskFile;
use model\Todo;
use todo\TodoFunctions;

require_once '../model/Todo.php';
require_once '../todo/TodoFunctions.php';
require_once '../model/TaskFile.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $todo = new Todo();
    $taskFile = new TaskFile();
    $todoFun = new TodoFunctions();

    $text = $_POST['text'];
    $dateTime = $_POST['dateTime'];
    $userId = $_POST['id'];


    if (isset($_POST['delete'])) {
        $deleteFileId = $_POST['fileId'];
        $deleted_directory = '../img/taskFiles/';
        $file = $taskFile->findFileById($deleteFileId);
        $deleteFileName = $file['files_name'];

        if ($file !== null) {
            $filePathToUpdate = $deleted_directory . $deleteFileName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }

        $deleteFile = $taskFile->deleteFileById($deleteFileId);
        $deleteId = $_POST['itemId'];
        $taskData = $todo->findTaskById($deleteId);
        $userId = $taskData['user_id'];
        $deleteResult = $todo->deleteById($deleteId);

        if ($deleteResult) {
            $count = $todo->getTaskCountByUserId($userId);
            $_SESSION['count'] = $count;

            $status = 0;
            $statusCount = $todo->findTaskCountByStatus($userId, $status);
            $_SESSION['status'] = $statusCount;

            $status = 1;
            $statusCount = $todo->findTaskCountByStatus($userId, $status);
            $_SESSION['status1'] = $statusCount;

            $status = 2;
            $statusCount = $todo->findTaskCountByStatus($userId, $status);
            $_SESSION['status2'] = $statusCount;

            $status = 3;
            $statusCount = $todo->findTaskCountByStatus($userId, $status);
            $_SESSION['status3'] = $statusCount;

            header('Location: ../view/singlePage.php');
            $todoFun->reloadTodoList();
        } else {
            $todoFun->handleError('delete_failed');
        }
    }

    if (isset($_POST['update'])) {
        $updateId = $_POST['itemId'];
        $task = $todo->findTaskById($updateId);

        $todoFun->updateTask($task);
    }

    if (isset($_POST['status']) && isset($_POST['itemId'])) {
        $status = $_POST['status'];
        $itemId = $_POST['itemId'];
        $selected = $todo->markCompletedById($itemId, $status);

        if (!$selected) {
            $todoFun->handleError('selected_failed');
        }

        $taskData = $todo->findTaskById($itemId);
        $userId = $taskData['user_id'];
        $status = 0;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status'] = $statusCount;

        $status = 1;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status1'] = $statusCount;

        $status = 2;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status2'] = $statusCount;

        $status = 3;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status3'] = $statusCount;

        header('Location: ../view/singlePage.php');

        $todoFun->reloadTodoList();
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
        header("Location: ../view/addTask.php?error=invalid_dateTime_extension");
        exit;
    }

    $saveResult = $todo->save($text, $dateTime, $userId);
    if ($saveResult) {
        if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {
            $taskId = $saveResult;
            $task = $todo->findTaskById($taskId);
            $taskUserId = $task['user_id'];
            $randomNumber = rand(10000, 1000000);
            $file_tmp_name = $_FILES['task_file']['tmp_name'];
            $file_name = $taskId . $randomNumber . $taskUserId . $_FILES['task_file']['name'];
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
            $todo->updateText($taskId, $text, $dateTime, $fileId);
        }

        $count = $todo->getTaskCountByUserId($userId);
        $_SESSION['count'] = $count;

        $status = 0;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status'] = $statusCount;

        $status = 1;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status1'] = $statusCount;

        $status = 2;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status2'] = $statusCount;

        $status = 3;
        $statusCount = $todo->findTaskCountByStatus($userId, $status);
        $_SESSION['status3'] = $statusCount;
        header("Location: ../view/singlePage.php");

        $todoFun->reloadTodoList();

    } else {
        $todoFun->handleError('save_failed');
    }
}