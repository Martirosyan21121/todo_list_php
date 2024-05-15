<?php

use model\User;
use model\UserPic;

require_once '../model/User.php';
require_once '../model/UserPic.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $userId = $_POST['id'];

    $user = new User();
    $userPic = new UserPic();

    if (strlen($username) < 5) {
        header("Location: ../view/updateUser.php?error=min_length");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../view/updateUser.php?error=invalid_email");
        exit;
    }

    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['user_image']['tmp_name'];
        $randomNumber = rand(1000, 1000000);
        $image_name = $userId . $randomNumber. $_FILES['user_image']['name'];
        $upload_directory = '../img/userPic/';

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            header("Location: ../view/updateUser.php?error=invalid_file_extension");
            exit;
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
    } else {
        $fileId = null;
        $uploaded_image_path = null;
        $image_name = null;
    }

    $userData = $user->getUserDataByEmail($email);
    $fileToUpdateId = $userData['files_id'];

    $fileToUpdate = $userPic->findFileById($fileToUpdateId);
    $fileToUpdateName = $fileToUpdate['files_name'];

    if ($fileId === null) {
        $fileToUpdateId = $userData['files_id'];
        if ($fileToUpdateId !== null && $fileToUpdateId !== $fileId) {
            if ($fileToUpdate !== null) {
                $filePathToUpdate = '../img/userPic/' . $fileToUpdateName;
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
            $filePathToUpdate = '../img/userPic/' . $fileToUpdateName;
            if (file_exists($filePathToUpdate)) {
                unlink($filePathToUpdate);
            }
        }
    }

    $updated = $user->updateUserById($username, $email, $fileId, $userId);

    if ($updated) {
        $userData = $user->getUserDataByEmail($email);

        $userPic->userPicPath($uploaded_image_path);
        $user->userData($userData);
        header("Location: ../view/singlePage.php");
    } else {
        echo "Update failed.";
    }
}

