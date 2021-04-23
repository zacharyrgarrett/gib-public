<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");

gibSecurity::permission_level(STUDENT);

if(isset($_POST['info-update-btn'])){

    $fname = isset($_POST["firstName"]) ? $_POST["firstName"] : false;
    $lname = isset($_POST["lastName"]) ? $_POST["lastName"] : false;
    $username = isset($_POST["username"]) ? $_POST["username"] : false;
    $fnameLength = (strlen($fname) > 0);
    $lnameLength = (strlen($lname) > 0);
    $unameLength = (strlen($username) > 0) && (strlen($username) < 20);
    $lengths = $fnameLength && $lnameLength && $unameLength;

    if($fname && $lname && $username && $lengths){

        $query = 'UPDATE gib_users 
                    SET first_name="'.$fname.'",
                    last_name="'.$lname.'",
                    username="'.$username.'" 
                    WHERE user_id='.$_SESSION['id'];
        $conn = new gibConnection($query);

        if($conn->success){
            $_SESSION["message"] = 'Your information has been updated';
            $_SESSION["type"] = 'alert-success';
            $_SESSION["firstName"] = $fname;
            $_SESSION["lastName"] = $lname;
            $_SESSION["username"] = $username;
        }
        else{
            $_SESSION["message"] = 'There was an error updating your information';
            $_SESSION["type"] = 'alert-danger';
        }
    }
    else{
        $_SESSION["message"] = 'Make sure all fields are populated';
        $_SESSION["type"] = 'alert-danger';
    }
}


if(isset($_POST['new-pw-btn'])){

    $password = isset($_POST["password"]) ? $_POST["password"] : false;
    $passwordConf = isset($_POST["passwordConf"]) ? $_POST["passwordConf"] : false;
    $pwLength = (6 <= strlen($password)) && (strlen($password) <= 20);

    if($password && $passwordConf && ($password == $passwordConf) && $pwLength){
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $query = 'UPDATE gib_users SET password="'.$password.'" WHERE user_id='.$_SESSION['id'];
        $conn = new gibConnection($query);

        if($conn->success){
            $_SESSION["message"] = 'Password has been changed';
            $_SESSION["type"] = 'alert-success';
        }
        else{
            $_SESSION["message"] = 'There was an error changing your password';
            $_SESSION["type"] = 'alert-danger';
        }
    }
    else{
        $_SESSION["message"] = 'Passwords must match with length of 6-20 characters';
        $_SESSION["type"] = 'alert-danger';
    }
}