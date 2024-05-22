<?php

namespace controller;

use DateTime;
use Exception;
use model\TaskFile;
use model\Todo;
use thecodeholic\phpmvc\Controller;
use thecodeholic\phpmvc\Request;

require_once 'model\Todo.php';
require_once 'model\TaskFile.php';

class TaskController extends Controller
{
    public function allUserTasks(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('allTasks', ['tasks' => $tasks]);
    }

    public function addTask(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'];
        return $this->render('addTask', ['id' => $userId]);
    }


    public function saveTask(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = new Todo();
            $taskFile = new TaskFile();
            $text = $_POST['text'];
            $dateTime = $_POST['dateTime'];

            $errors = [];

            date_default_timezone_set('Asia/Yerevan');
            $currentDateTime = new DateTime();
            $currentDateTime->modify('+10 minutes');

            try {
                $inputDateTime = new DateTime($dateTime);
            } catch (Exception $e) {
                $errors['invalid_date_time'] = "Invalid date and time format.";
                return $this->render('addTask', ['errors' => $errors]);
            }

            if ($inputDateTime < $currentDateTime) {
                $errors['invalid_date_time'] = "Please input a future date and time (at least 10 minutes from the current time).";
            }

            if (!empty($errors)) {
                return $this->render('addTask', ['errors' => $errors, 'id' => $userId]);
            }

            $saveResult = $taskModel->save($text, $dateTime, $userId);
            if ($saveResult) {
                if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {
                    $taskId = $saveResult;
                    $task = $taskModel->findTaskById($taskId);
                    $taskUserId = $task['user_id'];
                    $randomNumber = rand(10000, 1000000);
                    $file_tmp_name = $_FILES['task_file']['tmp_name'];
                    $file_name = $taskId . $randomNumber . $taskUserId . $_FILES['task_file']['name'];
                    $upload_directory = __DIR__ . '/../img/taskFiles/';
                    if (!file_exists($upload_directory)) {
                        mkdir($upload_directory, 0777, true);
                    }

                    $uploaded_image_path = $upload_directory . $file_name;

                    if (!move_uploaded_file($file_tmp_name, $uploaded_image_path)) {
                        exit;
                    }

                    $taskFile->saveFile($file_name);
                    $file = $taskFile->findFileByName($file_name);
                    $fileId = $file['id'];
                    $taskModel->updateText($taskId, $text, $dateTime, $fileId);
                }

//                $count = $taskModel->getTaskCountByUserId($userId);
//                $_SESSION['count'] = $count;
//
//                $status = 0;
//                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//                $_SESSION['status'] = $statusCount;
//
//                $status = 1;
//                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//                $_SESSION['status1'] = $statusCount;
//
//                $status = 2;
//                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//                $_SESSION['status2'] = $statusCount;
//
//                $status = 3;
//                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//                $_SESSION['status3'] = $statusCount;

                header('Location: /allTasks/' . $userId);
            }
        }
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('allTasks', ['tasks' => $tasks]);
    }

    public function deleteTask(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $deleted_directory = __DIR__ . '/../img/taskFiles/';

        $taskModel = new Todo();
        $taskFile = new TaskFile();

        $task = $taskModel->findTaskById($taskId);
        $userId = $task['user_id'];
        $fileId = $task['task_files_id'];
        $file = $taskFile->findFileById($fileId);
        $deleteFileName = $file['files_name'];

        if ($file !== null) {
            $filePathToUpdate = $deleted_directory . $deleteFileName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }

        $taskFile->deleteFileById($fileId);
        $deleteResult = $taskModel->deleteById($taskId);
        if ($deleteResult) {
//            $count = $todo->getTaskCountByUserId($userId);
//            $_SESSION['count'] = $count;
//
//            $status = 0;
//            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//            $_SESSION['status'] = $statusCount;
//
//            $status = 1;
//            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//            $_SESSION['status1'] = $statusCount;
//
//            $status = 2;
//            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//            $_SESSION['status2'] = $statusCount;
//
//            $status = 3;
//            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
//            $_SESSION['status3'] = $statusCount;
            header('Location: /allTasks/' . $userId);
        }
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('allTasks', ['tasks' => $tasks]);
    }

    public function showTaskUpdateForm(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $task = $taskModel->findTaskById($taskId);
        $userId = $task['user_id'];
        return $this->render('updateTask', ['task' => $task, 'userId' => $userId]);
    }

    public function taskStatus(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        var_dump($taskId);
    }

    public function updateTask(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $text = $_POST['text'];
            $dateTime = $_POST['dateTime'];
            $taskModel = new Todo();
            $taskFile = new TaskFile();

            if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {
                $task = $taskModel->findTaskById($taskId);
                $taskUserId = $task['user_id'];
                $randomNumber = rand(10000, 1000000);
                $file_tmp_name = $_FILES['task_file']['tmp_name'];
                $file_name = $taskId . $randomNumber . $taskUserId . $_FILES['task_file']['name'];
                $upload_directory = __DIR__ . '/../img/taskFiles/';

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
        }

        $todoData = $taskModel->findTaskById($taskId);
        $fileToUpdateId = $todoData['task_files_id'];

        $fileToUpdate = $taskFile->findFileById($fileToUpdateId);
        $fileToUpdateName = $fileToUpdate['files_name'];

        if ($fileId === null) {
            $fileToUpdateId = $todoData['task_files_id'];
            if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
                if ($fileToUpdate !== null) {
                    $filePathToUpdate = __DIR__ . '/../img/taskFiles/' . $fileToUpdateName;
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
                $filePathToUpdate = __DIR__ . '/../img/taskFiles/' . $fileToUpdateName;
                if (file_exists($filePathToUpdate)) {
                    unlink($filePathToUpdate);
                }
            }
        }

        $errors = [];

        date_default_timezone_set('Asia/Yerevan');
        $currentDateTime = new DateTime();
        $currentDateTime->modify('+10 minutes');

        try {
            $inputDateTime = new DateTime($dateTime);
        } catch (Exception $e) {
            $errors['invalid_date_time'] = "Invalid date and time format.";
            return $this->render('updateTask', ['errors' => $errors, 'userId' => $userId]);
        }

        if ($inputDateTime < $currentDateTime) {
            $errors['invalid_date_time'] = "Please input a future date and time (at least 10 minutes from the current time).";
        }

        $taskData = $taskModel->findTaskById($taskId);
        $userId = $taskData['user_id'];
        if (!empty($errors)) {
            return $this->render('updateTask', ['errors' => $errors, 'userId' => $userId]);
        }
        $taskData = $taskModel->findTaskById($taskId);
        $updateResult = $taskModel->updateText($taskId, $text, $dateTime, $fileId);
        if ($updateResult) {
            $userId = $taskData['user_id'];
            header('Location: /allTasks/' . $userId);
        } else {
            header('Location: /allTasks/update/' . $taskId);
        }
        return $this->render('allTasks', ['tasks' => $taskData]);
    }
}