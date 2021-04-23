<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConfiguration.php");

class gibConnection
{
    public $result;
    public $success;
    public int $num_rows;

    public function __construct($query,$db = "gib")
    {
        $db_server = gibConfiguration::$db_server;
        $db_username = gibConfiguration::$db_username;
        $db_password = gibConfiguration::$db_password;
        $db_name = $db;
        $mysqli = new mysqli($db_server,$db_username,$db_password,$db_name);
        $this->check_connection($mysqli);

        $this->success = mysqli_query($mysqli,$query);

        if($this->success && $this->success !== true){
            $this->result = $this->success->fetch_all(MYSQLI_ASSOC);
            $this->num_rows = sizeof($this->result);
        }
    }

    private function check_connection($mysqli){
        if($mysqli === false)
            die("ERROR: Could not connect. " . $mysqli->connect_error);
    }
}