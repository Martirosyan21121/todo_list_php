<?php

namespace model;

use database\DBConnection;
use mysqli;

require_once __DIR__ . '/../database/DBConnection.php';

class Todo extends DBConnection
{
    protected mysqli $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function save($text, $dateTime, $userId)
    {
        date_default_timezone_set('Asia/Yerevan');
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO todo.todo_list (text, date_time, user_id, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssis", $text, $dateTime, $userId, $created_at);
        $success = $stmt->execute();
        $taskId = $stmt->insert_id;
        $stmt->close();
        return $success ? $taskId : false;
    }

    public function getAllByUserId($userId)
    {
        $sql = "SELECT * FROM todo.todo_list WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return array();
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public function deleteById($todoId)
    {
        $sql = "DELETE FROM todo.todo_list WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $todoId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function updateStatus($todoId, $selectedValue)
    {
        if ($selectedValue < 0 || $selectedValue > 3) {
            return false;
        }
        $sql = "UPDATE todo.todo_list SET status = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ii", $selectedValue, $todoId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    public function findTaskById($todoId)
    {
        $sql = "SELECT * FROM todo.todo_list WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $todoId);
        $stmt->execute();
        $result = $stmt->get_result();

        $task = $result->fetch_assoc();
        $stmt->close();
        return $task ? : null;
    }

    public function updateText($todoId, $newText, $newDateTime, $fileId)
    {
        date_default_timezone_set('Asia/Yerevan');
        $updated_at = date('Y-m-d H:i:s');

        $sql = "UPDATE todo.todo_list SET text = ?, date_time = ?, created_at = ?, task_files_id = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("sssii", $newText, $newDateTime, $updated_at, $fileId, $todoId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getTaskCountByUserId($userId)
    {
        $sql = "SELECT COUNT(*) AS task_count FROM todo.todo_list WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($taskCount);
        $stmt->fetch();
        $stmt->close();
        return $taskCount;
    }

    public function findTaskCountByStatus($userId, $status)
    {
        $sql = "SELECT COUNT(*) AS task_count FROM todo.todo_list WHERE user_id = ? AND status = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ii", $userId, $status);
        $stmt->execute();
        $stmt->bind_result($taskCount);
        $stmt->fetch();
        $stmt->close();
        return $taskCount;
    }
}
