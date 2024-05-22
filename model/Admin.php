<?php

namespace model;
use database\DBConnection;
use mysqli;

require_once __DIR__ . '/../database/DBConnection.php';
class Admin extends DBConnection
{

    protected mysqli $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function findAdminById($adminId)
    {
        $sql = "SELECT * FROM todo.user WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();

        $admin = $result->fetch_assoc();
        $stmt->close();
        return $admin ? : null;
    }

    public function updateAdmin($id, $username, $email, $fileId)
    {
        $stmt = $this->connection->prepare("UPDATE todo.user SET username = ?, email = ?, files_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $fileId, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


//    public function getAllUserData()
//    {
//        $sql = "SELECT * FROM todo.user where user_type = 'USER'";
//        $result = $this->connection->query($sql);
//        return $result->fetch_all(MYSQLI_ASSOC);
//    }
//
//    public function deleteUserById($fileId)
//    {
//        $sql = "DELETE FROM todo.user WHERE id = ?";
//        $stmt = $this->connection->prepare($sql);
//        if (!$stmt) {
//            return false;
//        }
//        $stmt->bind_param("i", $fileId);
//        $success = $stmt->execute();
//        $stmt->close();
//        return $success;
//    }
}