<?php
require_once '../view/singlePage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['userId'];
    $_SESSION['id'] = $id;
    header('Location: ../view/addTask.php');
    exit();
}