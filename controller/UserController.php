<?php

namespace controller;

use model\User;
use model\UserPic;
use thecodeholic\phpmvc\Controller;

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

    public function logout()
    {
        $userModel = new User();
        $userModel->logout();
    }

    public function singlePage()
    {
        return $this->render('singlePage');
    }

    public function adminSinglePage()
    {
        return $this->render('adminSinglePage');
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userPic = new UserPic();
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
                    $userPic->userPicPath($uploaded_image_path);
                } else {
                    $userPic->userPicPath(null);
                }
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
                $userModel->userData($userData);
            } else {
                header('Location: /register/user?error=registration_failed');
            }
            exit();
        } else {
            return $this->render('register');
        }
    }

    public function showUpdateForm($id)
    {
        $userModel = new User();
        $user = $userModel->findOne(['id' => $id]);
        return $this->render('updateUser', ['user' => $user]);
    }

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                header('Location: /');
                exit();
            }

            $userModel = new User();
            $userPic = new UserPic();
            $userId = $_SESSION['user']['id'];
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $errors = [];

            if (strlen($username) < 5 || strlen($username) > 20) {
                $errors['username_length'] = "Username must be between 5 and 20 characters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Invalid email format.";
            }

            if (!empty($errors)) {
                return $this->render('updateUser', ['errors' => $errors]);
            }

            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
                $image_tmp_name = $_FILES['user_image']['tmp_name'];
                $randomNumber = rand(1000, 1000000);
                $image_name = $userId . $randomNumber. $_FILES['user_image']['name'];
                $upload_directory = __DIR__ . '/../img/userPic/';

                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                if (!in_array($file_extension, $allowed_extensions)) {
                    $errors['invalid_file_extension'] = "Invalid file extension. 
                                    Please upload a JPG, JPEG or PNG file.";
                    return $this->render('updateUser', ['errors' => $errors]);
                }
                if (!file_exists($upload_directory)) {
                    mkdir($upload_directory, 0777, true);
                }

                $uploaded_image_path = $upload_directory . $image_name;

                if (!move_uploaded_file($image_tmp_name, $uploaded_image_path)) {
                    header("Location: ../view/updateUser.php?error=file_upload_failed");
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

            $userData = $userModel->findUserByEmail($email);
            $fileToUpdateId = $userData['files_id'];

            $fileToUpdate = $userPic->findFileById($fileToUpdateId);
            $fileToUpdateName = $fileToUpdate['files_name'];

            if ($fileId === null) {
                $fileToUpdateId = $userData['files_id'];
                if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
                    if ($fileToUpdate !== null) {
                        $filePathToUpdate =  __DIR__ . '/../img/userPic/' . $fileToUpdateName;
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
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['files_id'] = $email;
                header('Location: /singlePage');
            } else {
                $errors['update_failed'] = "Update failed. Please try again.";
                return $this->render('updateUser', ['errors' => $errors, 'user' => ['id' => $userId, 'username' => $username, 'email' => $email]]);
            }
            exit();
        } else {
            header('Location: /user/update');
            exit();
        }
    }
}