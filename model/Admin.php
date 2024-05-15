<?php

namespace model;
use database\DBConnection;

require_once '../database/DBConnection.php';
class Admin extends DBConnection
{
    public function getAllUserData()
    {
        $sql = "SELECT * FROM todo.user where user_type = 'USER'";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteUserById($fileId)
    {
        $sql = "DELETE FROM todo.user WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $fileId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}