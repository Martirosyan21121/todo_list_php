<?php

use model\Todo;
use model\User;
use model\UserPic;

require_once '../model/User.php';
require_once '../model/Todo.php';
require_once '../model/UserPic.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User();
    $todo = new Todo();
    $userPic = new UserPic();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../login.php?error=invalid_email");
        exit;
    }
    session_start();
    $login = $user->login($email, $password);
    if ($login) {
        $userData = $user->getUserDataByEmail($email);

        if (!empty($userData['files_id'])) {
            $userFileId = $userData['files_id'];
            $file = $userPic->findFileById($userFileId);
            $image_name = $file['files_name'];

            $upload_directory = '../img/userPic/';
            $uploaded_image_path = $upload_directory . $image_name;
            $userPic->userPicPath($uploaded_image_path);
        }


        $user1 = $user->getUserDataByEmail($email);
        $userId = $user1['id'];
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

        $user->userData($userData);

    } else {
        header('Location: ../login.php?error=wrong_login');
    }
}
