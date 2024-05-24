<?php

namespace controller;

use DateTime;
use Exception;
use model\Admin;
use model\TaskFile;
use model\Todo;
use model\User;
use model\UserPic;
use thecodeholic\phpmvc\Controller;
use thecodeholic\phpmvc\Request;

require_once "model/Admin.php";
require_once "model/User.php";
require_once "model/Todo.php";
require_once "model/UserPic.php";
require_once "model/TaskFile.php";

class AdminController extends Controller
{

    public function adminSinglePage(Request $request)
    {
        $adminId = (int)$request->getRouteParams()['id'] ?? null;
        $adminModel = new Admin();
        $admin = $adminModel->findAdminById($adminId);
        return $this->render('adminSinglePage', ['admin' => $admin]);
    }

    public function adminSinglePageShow()
    {
        return $this->render('adminSinglePage');
    }

    public function showAdminUpdateForm(Request $request)
    {
        $adminId = (int)$request->getRouteParams()['id'] ?? null;
        $adminModel = new Admin();
        $admin = $adminModel->findAdminById($adminId);
        return $this->render('updateAdmin', ['admin' => $admin]);
    }

    public function updateAdmin(Request $request)
    {
        $adminId = (int)$request->getRouteParams()['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminModel = new Admin();
            $adminPic = new UserPic();
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $errors = [];
            $admin = $adminModel->findAdminById($adminId);
            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 5 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!empty($errors)) {
                return $this->render('updateAdmin', ['errors' => $errors, 'admin' => $admin]);
            }

            if (isset($_FILES['admin_image']) && $_FILES['admin_image']['error'] === UPLOAD_ERR_OK) {
                $image_tmp_name = $_FILES['admin_image']['tmp_name'];
                $randomNumber = rand(1000, 1000000);
                $image_name = $adminId . $randomNumber . $_FILES['admin_image']['name'];

                $upload_directory = __DIR__ . '/../img/userPic/';
                $allowed_extensions = ['jpg', 'jpeg', 'png'];

                $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                if (!in_array($file_extension, $allowed_extensions)) {
                    $errors['invalid_file_extension'] = "Invalid file extension. 
                                    Please upload a JPG, JPEG or PNG file.";
                    return $this->render('updateAdmin', ['errors' => $errors, 'admin' => $admin]);
                }

                if (!file_exists($upload_directory)) {
                    mkdir($upload_directory, 0777, true);
                }

                $uploaded_image_path = $upload_directory . $image_name;

                if (!move_uploaded_file($image_tmp_name, $uploaded_image_path)) {
                    exit;
                }

                $adminPic->savePic($image_name);
                $file = $adminPic->findFileByName($image_name);
                $fileId = $file['id'];
                $_SESSION['admin_pic_path'] = '/img/userPic/' . $image_name;
            } else {
                $_SESSION['admin_pic_path'] = null;
                $fileId = null;
            }
        }

        $adminData = $adminModel->findAdminById($adminId);
        $fileToUpdateId = $adminData['files_id'];

        $fileToUpdate = $adminPic->findFileById($fileToUpdateId);
        $fileToUpdateName = $fileToUpdate['files_name'];

        if ($fileId === null) {
            $fileToUpdateId = $adminData['files_id'];
            if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
                if ($fileToUpdate !== null) {
                    $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToUpdateName;
                    if (file_exists($filePathToUpdate)) {
                        unlink($filePathToUpdate);
                    }
                }
            }
            if ($fileToUpdateId !== null) {
                $adminPic->deleteFileById($fileToUpdateId);
            }
        }

        if (!empty($fileId)) {
            if ($fileToUpdateId !== null) {
                $adminPic->deleteFileById($fileToUpdateId);
                $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToUpdateName;
                if (file_exists($filePathToUpdate)) {
                    unlink($filePathToUpdate);
                }
            }
        }
        $updateResult = $adminModel->updateAdmin($adminId, $username, $email, $fileId);
        if ($updateResult) {
            header('Location: /adminPage/' . $adminId);
        } else {
            header('Location: /admin/update/' . $adminId);
        }
        return $this->render('adminSinglePage', ['admin' => $admin]);
    }

    public function showAllUsers()
    {
        $adminModel = new Admin();
        $allUsers = $adminModel->getAllActiveUsers();
        return $this->render('allUsers', ['allUsers' => $allUsers]);
    }

    public function deleteUser(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $user = new User();
        $userPic = new UserPic();
        $todo = new Todo();
        $taskFile = new TaskFile();
        $userData = $user->findUserById($userId);
        $userPicId = $userData['files_id'];
        if ($userPicId !== null) {
            $fileToUpdate = $userPic->findFileById($userData['files_id']);
            $fileToUpdateName = $fileToUpdate['files_name'];
            $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToUpdateName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
            $userPic->deleteFileById($userData['files_id']);
        }
        $allTasks = $todo->getAllByUserId($userId);
        foreach ($allTasks as $task) {
            if ($task['task_files_id'] !== null) {
                $fileToDelete = $taskFile->findFileById($task['task_files_id']);
                $fileToDeleteName = $fileToDelete['files_name'];
                $filePathToDelete = __DIR__ . '/../img/taskFiles/' . $fileToDeleteName;
                if (file_exists($filePathToDelete)) {
                    unlink($filePathToDelete);
                }
                $taskFile->deleteFileById($task['task_files_id']);
            }
        }

        $user->deleteUserById($userId);
        $adminModel = new Admin();
        $allUsers = $adminModel->getAllDeActiveUsers();
        return $this->render('adminDeactivatedUsers', ['allUsers' => $allUsers]);
    }

    public function deactivateUsers()
    {
        $adminModel = new Admin();
        $allUsers = $adminModel->getAllDeActiveUsers();
        return $this->render('adminDeactivatedUsers', ['allUsers' => $allUsers]);
    }

    public function deactivate(Request $request)
    {
        $adminModel = new Admin();
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $adminModel->deactivateUserById($userId);
        $allUsers = $adminModel->getAllDeActiveUsers();
        return $this->render('adminDeactivatedUsers', ['allUsers' => $allUsers]);
    }

    public function activateUser(Request $request)
    {
        $adminModel = new Admin();
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $adminModel->activateUserById($userId);
        $allUsers = $adminModel->getAllActiveUsers();
        return $this->render('allUsers', ['allUsers' => $allUsers]);
    }

    public function editUserPage(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $userModel = new User();
        $user = $userModel->findUserById($userId);
        return $this->render('editUser', ['user' => $user]);
    }

    public function updateUserByAdmin(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel = new User();
            $userPic = new UserPic();
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $errors = [];
            $user = $userModel->findUserById($userId);

            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 5 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!empty($errors)) {
                return $this->render('editUser', ['errors' => $errors, 'user' => $user]);
            }

            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
                $image_tmp_name = $_FILES['user_image']['tmp_name'];
                $randomNumber = rand(1000, 1000000);
                $image_name = $userId . $randomNumber . $_FILES['user_image']['name'];
                $upload_directory = __DIR__ . '/../img/userPic/';

                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                if (!in_array($file_extension, $allowed_extensions)) {
                    $errors['invalid_file_extension'] = "Invalid file extension. 
                                    Please upload a JPG, JPEG or PNG file.";
                    return $this->render('editUser', ['errors' => $errors, 'user' => $user]);
                }
                if (!file_exists($upload_directory)) {
                    mkdir($upload_directory, 0777, true);
                }

                $uploaded_image_path = $upload_directory . $image_name;

                if (!move_uploaded_file($image_tmp_name, $uploaded_image_path)) {
                    exit;
                }

                $userPic->savePic($image_name);
                $file = $userPic->findFileByName($image_name);
                $fileId = $file['id'];
                $_SESSION['pic_path'] = '/img/userPic/' . $image_name;
            } else {
                $_SESSION['pic_path'] = null;
                $fileId = null;
            }
        }

        $userData = $userModel->findUserById($userId);
        $fileToUpdateId = $userData['files_id'];

        $fileToUpdate = $userPic->findFileById($fileToUpdateId);
        $fileToUpdateName = $fileToUpdate['files_name'];

        if ($fileId === null) {
            $fileToUpdateId = $userData['files_id'];
            if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
                if ($fileToUpdate !== null) {
                    $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToUpdateName;
                    if (file_exists($filePathToUpdate)) {
                        unlink($filePathToUpdate);
                    }
                }
            }
            if ($fileToUpdateId !== null) {
                $userPic->deleteFileById($fileToUpdateId);
            }
        }

        if (!empty($fileId)) {
            if ($fileToUpdateId !== null) {
                $userPic->deleteFileById($fileToUpdateId);
                $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToUpdateName;
                if (file_exists($filePathToUpdate)) {
                    unlink($filePathToUpdate);
                }
            }
        }

        $updateResult = $userModel->updateUser($userId, $username, $email, $fileId);
        if ($updateResult) {
            header('Location: /admin/showAllUsers');
        } else {
            header('Location: /admin/updateData/' . $userId);
        }
        return $this->render('allUsers');
    }

    public function allTasks(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('userAllTasksForAdmin', ['tasks' => $tasks, 'userId' => $userId]);
    }

    public function deleteTaskByAdmin(Request $request)
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
            header('Location: /admin/showAllUsers/allTasks/' . $userId);
        }
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('userAllTasksForAdmin', ['tasks' => $tasks]);
    }
    public function addTaskPage(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        return $this->render('addTaskByAdmin', ['id' => $userId]);
    }

    public function addTask(Request $request)
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
                return $this->render('addTaskByAdmin', ['errors' => $errors]);
            }

            if ($inputDateTime < $currentDateTime) {
                $errors['invalid_date_time'] = "Please input a future date and time (at least 10 minutes from the current time).";
            }

            if (!empty($errors)) {
                return $this->render('addTaskByAdmin', ['errors' => $errors, 'id' => $userId]);
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
                header('Location: /admin/showAllUsers/allTasks/' . $userId);
            }
        }
        $tasks = $taskModel->getAllByUserId($userId);
        return $this->render('userAllTasksForAdmin', ['tasks' => $tasks]);
    }

    public function updateTaskPage(Request $request)
    {
        $taskId = (int)$request->getRouteParams()['id'] ?? null;
        $taskModel = new Todo();
        $task = $taskModel->findTaskById($taskId);
        $userId = $task['user_id'];
        return $this->render('updateTaskByAdmin', ['task' => $task, 'userId' => $userId]);
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
                    exit;
                }

                $taskFile->saveFile($file_name);
                $file = $taskFile->findFileByName($file_name);
                $fileId = $file['id'];
            } else {
                $fileId = null;
            }
            $errors = [];

            date_default_timezone_set('Asia/Yerevan');
            $currentDateTime = new DateTime();
            $currentDateTime->modify('+10 minutes');
            $taskData = $taskModel->findTaskById($taskId);
            $userId = $taskData['user_id'];
            try {
                $inputDateTime = new DateTime($dateTime);
            } catch (Exception $e) {
                $errors['invalid_date_time'] = "Invalid date and time format.";
                return $this->render('updateTaskByAdmin', ['errors' => $errors, 'userId' => $userId, 'task' => $taskData]);
            }

            if ($inputDateTime < $currentDateTime) {
                $errors['invalid_date_time'] = "Please input a future date and time (at least 10 minutes from the current time).";
            }

            if (!empty($errors)) {
                return $this->render('updateTaskByAdmin', ['errors' => $errors, 'userId' => $userId, 'task' => $taskData]);
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

        $updateResult = $taskModel->updateText($taskId, $text, $dateTime, $fileId);
        if ($updateResult) {
            $userId = $taskData['user_id'];
            header('Location: /admin/showAllUsers/allTasks/' . $userId);
        } else {
            header('Location: /admin/showAllUsers/allTasks/updatePage/' . $taskId);
        }
        return $this->render('userAllTasksForAdmin', ['tasks' => $taskData]);
    }
}