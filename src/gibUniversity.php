<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");

class gibUniversity
{

    public $name;
    public $abbreviation;
    public $domain;
    public $active;
    public $db;


    public function __construct($university_id = false)
    {
        if(!$university_id){
            $university_id = $_SESSION["universityID"];
        }
        $query = 'SELECT * FROM university_info WHERE ID='.$university_id.' LIMIT 1';
        $conn = new gibConnection($query);
        if($conn->num_rows){
            $result = $conn->result[0];
            $this->name = $result["School"];
            $this->abbreviation = $result["Abbreviation"];
            $this->domain = $result["Domain"];
            $this->active = boolval($result["Active"]);
            $this->db = $result["DB"];
        }
    }

    public static function get_university_db($university_id = false){
        if(!$university_id){
            $university_id = $_SESSION["universityID"];
        }
        $query = 'SELECT DB FROM university_info WHERE ID='.$university_id.' LIMIT 1';
        $conn = new gibConnection($query);
        $db = false;
        if($conn->num_rows){
            $db = $conn->result[0]["DB"];
        }
        return $db;
    }

    public static function get_university_abbrev($university_id = false){
        if(!$university_id){
            $university_id = $_SESSION["universityID"];
        }
        $query = 'SELECT Abbreviation FROM university_info WHERE ID='.$university_id.' LIMIT 1';
        $conn = new gibConnection($query);
        $abbrev = false;
        if($conn->num_rows){
            $abbrev = $conn->result[0]["Abbreviation"];
        }
        return $abbrev;
    }

    public static function get_session_university_id(){
        return isset($_SESSION["universityID"]) ? $_SESSION["universityID"] : -1;
    }
}