<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");

$thisPage = new gibPage("Verify Email");

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $sql = 'SELECT * FROM gib_users where token="'.$token.'" LIMIT 1';
    $conn = new gibConnection($sql);

    if($conn->num_rows > 0){
        $user = $conn->result[0];
        $query = 'UPDATE gib_users SET verified=1 WHERE token="'.$token.'"';
        $conn = new gibConnection($query);
        if($conn->success){
            $_SESSION["id"] = $user["user_id"];
            $_SESSION["universityID"] = $user["university_id"];
            $_SESSION["firstName"] = $user["first_name"];
            $_SESSION["lastName"] = $user["last_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["verified"] = true;
            $_SESSION["message"] = "Your email address has been verified!";
            $_SESSION["type"] = "alert-success";
            header("location: https://gibbl.io");
            exit();
        }
    }
    else{
        $html = '<p>User was not found.</p>';
        $thisPage->add_content($html);
    }
}
else{
    $html = '<p>No token provided. Click the link from the verification email.</p>';
    $thisPage->add_content($html);
}

$thisPage->write_page();