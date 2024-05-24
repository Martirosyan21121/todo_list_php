<?php
namespace model;
use database\DBConnection;
use mysqli;
require_once __DIR__ . '/../database/DBConnection.php';

class UserPic extends DBConnection
{

    protected mysqli $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = $this->getConnection();
    }
    public function savePic($imageName)
    {
        $sql = "INSERT INTO todo.files (files_name) VALUES (?)";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $imageName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function userPicPath($picPath, $email)
    {
        $userModel = new User();
        $user = $userModel->findUserByEmail($email);
        if ($user['user_type'] === 'USER'){
            $_SESSION['pic_path'] = $picPath;
            header('Location: /singlePage');
        } else if ($user['user_type'] === 'ADMIN') {
            $_SESSION['admin_pic_path'] = $picPath;
            header('Location: /singlePage');
        }
    }

    public function findFileByName($fileName)
    {
        $sql = "SELECT * FROM todo.files WHERE files_name = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file;
    }

    public function findFileById($fileId)
    {
        $sql = "SELECT * FROM todo.files WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file;
    }

    public function deleteFileById($fileId)
    {
        $sql = "DELETE FROM todo.files WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}