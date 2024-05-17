<?php
require_once '../views/singlePage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['userId'];
    $_SESSION['id'] = $id;
    header('Location: ../views/addTask.php');
    exit();
}