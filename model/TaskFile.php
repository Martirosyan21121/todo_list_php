<?php
namespace model;

use database\DBConnection;

require_once '../database/DBConnection.php';

class TaskFile extends DBConnection
{
    public function saveFile($fileName)
    {
        $sql = "INSERT INTO todo.files (files_name) VALUES (?)";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $fileName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function findFileByName($taskFileName)
    {
        $sql = "SELECT * FROM todo.files WHERE files_name = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $taskFileName);
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
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function findFileById($taskFileId)
    {
        $sql = "SELECT * FROM todo.files WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $taskFileId);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file;
    }

}