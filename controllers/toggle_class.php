<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");

gibSecurity::permission_level(1);

if(isset($_POST["course_code"])){
    $course_id = $_POST["course_code"];
    $user_id = gibUser::get_user_id();
    $db = gibUniversity::get_university_db();
    $query = 'SELECT * FROM saved_courses WHERE course_code="'.$course_id.'" AND user_id='.$user_id.' LIMIT 1';
    $con = new gibConnection($query,$db);

    if($con->num_rows){
        $query = 'DELETE from saved_courses WHERE save_id='.$con->result[0]["save_id"];
    }
    else{
        $query = 'INSERT INTO saved_courses (course_code,user_id) VALUES ("'.$course_id.'","'.$user_id.'")';
    }
    $con = new gibConnection($query,$db);
}