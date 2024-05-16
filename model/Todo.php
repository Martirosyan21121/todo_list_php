<?php
namespace model;
use database\DBConnection;
require_once __DIR__ . '/../database/DBConnection.php';

class Todo extends DBConnection
{
    public function save($text, $dataTime ,$id)
    {
        date_default_timezone_set('Asia/Yerevan');

        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO todo.todo_list (text ,date_time ,user_id, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssis", $text, $dataTime, $id, $created_at);
        $success = $stmt->execute();
        $taskId = $stmt->insert_id;
        $stmt->close();
        return ($success ? $taskId : false);
    }

    public function getAllByUserId($userId)
    {
        $sql = "SELECT * FROM todo.todo_list WHERE user_id = '$userId'";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return array();
        }
    }


    public function deleteById($todoId)
    {
        $sql = "DELETE FROM todo.todo_list WHERE id = '$todoId'";
        return $this->connection->query($sql);
    }

    public function markCompletedById($todoId, $selectedValue)
    {
        if ($selectedValue < 0 || $selectedValue > 3) {
            return false;
        }
        $sqlUpdate = "UPDATE todo.todo_list SET status = '$selectedValue' WHERE id = '$todoId'";
        return $this->connection->query($sqlUpdate);
    }

    public function findTaskById($todoId)
    {
        $sql = "SELECT * FROM todo.todo_list WHERE id = '$todoId'";
        $result = $this->connection->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return array();
        }
    }

    public function updateText($todoId, $newText, $newDateTime, $fileId)
    {
        date_default_timezone_set('Asia/Yerevan');
        $updated_at = date('Y-m-d H:i:s');

        $sql = "UPDATE todo.todo_list SET text = ?,  date_time = ?, created_at = ?, task_files_id = ? WHERE id = ?";
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
        $stmt->bind_param("is", $userId, $status);
        $stmt->execute();
        $stmt->bind_result($taskCount);
        $stmt->fetch();
        $stmt->close();
        return $taskCount;
    }
}