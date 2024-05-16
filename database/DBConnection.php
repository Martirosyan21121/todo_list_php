<?php
namespace database;
use mysqli;

class DBConnection
{
    private string $DBhost = "localhost";
    private string $DBusername = "root";
    private string $DBpassword = "";
    private string $DBdatabase = "todo";

    protected mysqli $connection;


    public function __construct()
    {
        $this->connection = new mysqli($this->DBhost, $this->DBusername, $this->DBpassword, $this->DBdatabase);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getDBHost(): string
    {
        return $this->DBhost;
    }

    public function getDBUsername(): string
    {
        return $this->DBusername;
    }

    public function getDBPassword(): string
    {
        return $this->DBpassword;
    }

    public function getDBDatabase(): string
    {
        return $this->DBdatabase;
    }


}