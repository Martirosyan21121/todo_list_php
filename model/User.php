<?php

namespace model;

use database\DBConnection;
use mysqli;

require_once __DIR__ . '/../database/DBConnection.php';
class User extends DBConnection
{
    protected mysqli $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function register($username, $email, $password)
    {
        $hashed_password = md5($password);
        $stmt = $this->connection->prepare("INSERT INTO todo.user (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function emailExists($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM todo.user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function login($email, $password)
    {
        $user = $this->findOne($email);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

    public function findOne($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM todo.user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function userData($user)
    {
        $_SESSION['user'] = $user;

        if ($user['user_type'] === 'ADMIN') {
            header("Location: /adminPage");
        } else {
            header("Location: /singlePage");
        }
    }
}
