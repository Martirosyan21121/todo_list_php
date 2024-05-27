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

    public static function primaryKey(): string
    {
        return 'id';
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
        $hashed_password = md5($password);
        $stmt = $this->connection->prepare("SELECT * FROM todo.user WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return true;
        }
        return false;
    }

    public function findUserByEmail($email)
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

    public function deleteUserById($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM todo.user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function findUserById($userId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM todo.user WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public static function findOne($condition)
    {
        $primaryKey = static::primaryKey();
        $connection = (new static())->getConnection();
        $tableName = 'todo.user';

        $sql = "SELECT * FROM $tableName WHERE $primaryKey = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $condition);
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
        if ($user['status'] === 1) {
            header("Location: /deactivate");
        } else if ($user['user_type'] === 'ADMIN') {
            header("Location: /adminPage/" . $user['id']);
        } else {
            header("Location: /singlePage/" . $user['id']);
        }
    }


    public function userRegisterData($user)
    {
        $_SESSION['user'] = $user;
        $_SESSION['pic_path'] = null;
        header("Location: /register/gmailSend/" . $user['id']);
    }

    public function updateUser($id, $username, $email, $fileId)
    {
        $stmt = $this->connection->prepare("UPDATE todo.user SET username = ?, email = ?, files_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $fileId, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();

        session_destroy();
        var_dump($_SESSION);
        header('Location: /');
        exit();
    }
}