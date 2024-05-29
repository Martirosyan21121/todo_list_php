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

                $count = $taskModel->getTaskCountByUserId($userId);
                $_SESSION['count'] = $count;

                $status = 0;
                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
                $_SESSION['status'] = $statusCount;

                $status = 1;
                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
                $_SESSION['status1'] = $statusCount;

                $status = 2;
                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
                $_SESSION['status2'] = $statusCount;

                $status = 3;
                $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
                $_SESSION['status3'] = $statusCount;

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
            $count = $taskModel->getTaskCountByUserId($userId);
            $_SESSION['count'] = $count;

            $status = 0;
            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
            $_SESSION['status'] = $statusCount;

            $status = 1;
            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
            $_SESSION['status1'] = $statusCount;

            $status = 2;
            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
            $_SESSION['status2'] = $statusCount;

            $status = 3;
            $statusCount = $taskModel->findTaskCountByStatus($userId, $status);
            $_SESSION['status3'] = $statusCount;
            header('Location: /allTasks/' . $userId);
        }
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('allTasks', ['tasks' => $tasks]);
    }

    public function deleteTaskFile(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $taskFileModel = new TaskFile();

        $task = $taskModel->findTaskById($taskId);
        $taskFile = $taskFileModel->findFileById($task['task_files_id']);

        $fileToDeleteName = $taskFile['files_name'];
        $fileToDelete = $task['task_files_id'];
        if ($fileToDelete) {
            $filePathToUpdate = __DIR__ . '/../img/taskFiles/' . $fileToDeleteName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }
        $userId = $task['user_id'];

        $taskFileModel->deleteFileById($task['task_files_id']);
        return $this->render('updateTask', ['task' => $task, 'userId' => $userId]);
    }

    public function showTaskUpdateForm(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $taskFileModel = new TaskFile();
        $task = $taskModel->findTaskById($taskId);
        $taskFile = $taskFileModel->findFileById($task['task_files_id']);
        $userId = $task['user_id'];
        if (!empty($taskFile)) {
            $fileName = $taskFile['files_name'];
            $subName = strrpos($fileName, '.');
            $showName = substr($fileName, $subName + 1);
            return $this->render('updateTask', ['task' => $task, 'userId' => $userId, 'fileName' => '.'.$showName]);
        }
        return $this->render('updateTask', ['task' => $task, 'userId' => $userId]);
    }

    public function taskStatus(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $status = (int)$_GET['status'] ?? null;
        $taskModel = new Todo();

        if ($taskId !== null && $status !== null) {
            $taskModel->updateStatus($taskId, $status);
        }
        $task = $taskModel->findTaskById($taskId);
        $userId = $task['user_id'];
        header('Location: /allTasks/' . $userId);
    }

    public function updateTask(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $text = $_POST['text'];
            $dateTime = $_POST['dateTime'];
            $taskModel = new Todo();
            $taskFileModel = new TaskFile();

            $errors = [];
            $task = $taskModel->findTaskById($taskId);
            $taskFile = $taskFileModel->findFileById($task['task_files_id']);
            $taskFileName = '';
            if (!empty($taskFile)) {
                $taskFileName = $taskFile['files_name'];
            }
            $showName = substr($taskFileName, -4);

            date_default_timezone_set('Asia/Yerevan');
            $currentDateTime = new DateTime();
            $currentDateTime->modify('+10 minutes');
            $taskData = $taskModel->findTaskById($taskId);
            $userId = $taskData['user_id'];
            try {
                $inputDateTime = new DateTime($dateTime);
            } catch (Exception $e) {
                $errors['invalid_date_time'] = "Invalid date and time format.";
                return $this->render('updateTask', ['errors' => $errors, 'userId' => $userId, 'task' => $taskData,  'fileName' => $showName]);
            }

            if ($inputDateTime < $currentDateTime) {
                $errors['invalid_date_time'] = "Please input a future date and time (at least 10 minutes from the current time).";
            }

            if (!empty($errors)) {
                return $this->render('updateTask', ['errors' => $errors, 'userId' => $userId, 'task' => $taskData,  'fileName' => $showName]);
            }

            $file_Id = $task['task_files_id'];
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
                    exit;
                }

                $taskFileModel->saveFile($file_name);
                $file = $taskFileModel->findFileByName($file_name);
                $fileId = $file['id'];
            } else if (!empty($file_Id)){
                $updateResult = $taskModel->updateText($taskId, $text, $dateTime, $file_Id);
                if ($updateResult) {
                    header('Location: /allTasks/' . $userId);
                } else {
                    header('Location: /allTasks/update/' . $taskId);
                }
                return $this->render('allTasks', ['task' => $taskId]);
            } else {
                $fileId = null;
            }

        }

        $todoData = $taskModel->findTaskById($taskId);
        $fileToUpdateId = $todoData['task_files_id'];

        $fileToUpdate = $taskFileModel->findFileById($fileToUpdateId);
        $fileToUpdateName = $fileToUpdate['files_name'];

        if (!empty($fileId)) {
            if ($fileToUpdateId !== null) {
                $taskFileModel->deleteFileById($fileToUpdateId);
                $filePathToUpdate = __DIR__ . '/../img/taskFiles/' . $fileToUpdateName;
                if (file_exists($filePathToUpdate)) {
                    unlink($filePathToUpdate);
                }
            }
        }


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