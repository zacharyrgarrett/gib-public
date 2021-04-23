<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");

class gibUser
{
    public $signed_in;
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $username;
    private $university;
    public $verified;
    public $security_level;

    public function __construct()
    {
        if(isset($_SESSION["id"])){
            $this->signed_in = true;
            $this->user_id = $_SESSION["id"];
            $this->first_name = $_SESSION["firstName"];
            $this->last_name = $_SESSION["lastName"];
            $this->email = $_SESSION["email"];
            $this->username = $_SESSION["username"];
            $this->verified = $_SESSION["verified"];
            $this->security_level = $_SESSION["securityLevel"];
            $this->university = new gibUniversity($_SESSION["universityID"]);
        }
        else{
            $this->signed_in = false;
        }
    }

    public function get_university_name(){
        return $this->university->name;
    }

    public function get_university_abbreviation(){
        return $this->university->abbreviation;
    }

    public static function get_username($user_id){
        $query = 'SELECT username FROM gib_users WHERE user_id='.$user_id.' LIMIT 1';
        $conn = new gibConnection($query);
        $username = false;
        if($conn->num_rows){
            $username = $conn->result[0]["username"];
        }
        return $username;
    }

    public static function get_user_id(){
        return isset($_SESSION["id"]) ? $_SESSION["id"] : -1;
    }
}