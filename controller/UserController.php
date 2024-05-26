<?php

namespace controller;

use model\Todo;
use model\User;
use model\UserPic;
use thecodeholic\phpmvc\Controller;
use thecodeholic\phpmvc\Request;

require_once 'model\User.php';
require_once 'model\UserPic.php';

class UserController extends Controller
{
    public function home()
    {
        return $this->render('login');
    }

    public function register()
    {
        return $this->render('register');
    }

    public function deactivate()
    {
        return $this->render('accountDeactivate');
    }

    public function logout()
    {
        $userModel = new User();
        $userModel->logout();
    }

    public function singlePageShow()
    {
        return $this->render('singlePage');
    }

    public function singlePage(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $userModel = new User();
        $taskModel = new Todo();
        $user = $userModel->findUserById($userId);

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
        if (empty($user['files_id'])){
            $_SESSION['pic_path'] = null;
        }
            return $this->render('singlePage', ['user' => $user]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userPic = new UserPic();
            $taskModel = new Todo();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!empty($errors)) {
                return $this->render('login', ['errors' => $errors]);
            }

            if (!$userModel->emailExists($email)) {
                $errors['email_not_exists'] = "Email not found.";
                return $this->render('login', ['errors' => $errors]);
            }
            $login = $userModel->login($email, $password);

            if ($login) {
                $userData = $userModel->findUserByEmail($email);
                if (!empty($userData['files_id'])) {
                    $userFileId = $userData['files_id'];
                    $file = $userPic->findFileById($userFileId);
                    $image_name = $file['files_name'];

                    $upload_directory = '../img/userPic/';
                    $uploaded_image_path = $upload_directory . $image_name;
                    $userPic->userPicPath($uploaded_image_path, $email);
                } else {
                    $userPic->userPicPath(null, $email);
                }
                $user1 = $userModel->findUserByEmail($email);
                $userId = $user1['id'];
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

                $userModel->userData($userData);
            } else {
                $errors['login_failed'] = "Invalid email or password.";
                return $this->render('login', ['errors' => $errors]);
            }
            exit();
        } else {
            return $this->render('login');
        }
    }

    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = [];

            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 5 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
                $errors['password_length'] = "Password need to be least 8 characters must be 
                used letters(uppercase and lowercase), numbers and symbols. ";
            }

            if ($userModel->emailExists($email)) {
                $errors['email_exists'] = "Email already exists.";
            }

            if (!empty($errors)) {
                return $this->render('register', ['errors' => $errors]);
            }

            $registrationResult = $userModel->register($username, $email, $password);
            if ($registrationResult) {
                $userData = $userModel->findUserByEmail($email);
                $userModel->userRegisterData($userData);

            } else {
                header('Location: /register');
            }
            exit();
        } else {
            return $this->render('register');
        }
    }

    public function showUpdateForm(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $userModel = new User();
        $userPic = new UserPic();

        $user = $userModel->findUserById($userId);
        $userImage = $userPic->findFileById($user['files_id']);
        if (!empty($userImage)) {
            $picName = $userImage['files_name'];
            $showName = substr($picName, -4);
            return $this->render('updateUser', ['user' => $user, 'picName' => $showName]);
        }
        return $this->render('updateUser', ['user' => $user]);
    }

    public function deletePic(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;
        $userPicModel = new UserPic();
        $userModel = new User();
        $user = $userModel->findUserById($userId);
        $userImage = $userPicModel->findFileById($user['files_id']);

        $fileToDeleteName = $userImage['files_name'];
        $fileToDelete = $user['files_id'];
        if ($fileToDelete) {
            $filePathToUpdate = __DIR__ . '/../img/userPic/' . $fileToDeleteName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }

        $userPicModel->deleteFileById($user['files_id']);
        return $this->render('updateUser', ['user' => $user]);
    }

    public function updateUser(Request $request)
    {
        $userId = (int)$request->getRouteParams()['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel = new User();
            $userPic = new UserPic();
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $errors = [];
            $user = $userModel->findUserById($userId);
            $userImage = $userPic->findFileById($user['files_id']);
            $picName = '';
            if (!empty($userImage)) {
                $picName = $userImage['files_name'];
            }
            $showName = substr($picName, -4);

            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 5 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!empty($errors)) {
                return $this->render('updateUser', ['errors' => $errors, 'user' => $user, 'picName' => $showName]);
            }
            $file_Id = $user['files_id'];
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
                    return $this->render('updateUser', ['errors' => $errors, 'user' => $user]);
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

            } else if (!empty($file_Id)) {
                $userImage = $userPic->findFileById($file_Id);
                $userPicName = $userImage['files_name'];
                $_SESSION['pic_path'] = '/img/userPic/' . $userPicName;
                $updateResult = $userModel->updateUser($userId, $username, $email, $file_Id);
                if ($updateResult) {
                    header('Location: /singlePage/' . $userId);
                } else {
                    header('Location: /user/update/' . $userId);
                }
                return $this->render('singlePage', ['user' => $userId]);
            } else {
                $_SESSION['pic_path'] = null;
                $fileId = null;
            }
        }

        $userData = $userModel->findUserById($userId);
        $fileToUpdateId = $userData['files_id'];

        $fileToUpdate = $userPic->findFileById($fileToUpdateId);
        $fileToUpdateName = $fileToUpdate['files_name'];

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
            header('Location: /singlePage/' . $userId);
        } else {
            header('Location: /user/update/' . $userId);
        }
        return $this->render('singlePage', ['user' => $userId]);
    }


}