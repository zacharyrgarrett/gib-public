<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibEmail.php");

$username = "";
$email = "";
$errors = Array();

//Sign up
if(isset($_POST["signup-btn"])){
    if(empty($_POST["universityID"]))
        $errors["universityID"] = "School required";
    if(empty($_POST["firstName"]))
        $errors["firstName"] = "First name required";
    if(empty($_POST["lastName"]))
        $errors["lastName"] = "Last name required";
    if(empty($_POST["email"]))
        $errors["email"] = "Email required";
    if(empty($_POST["username"]))
        $errors["username"] = "Username required";
    if(empty($_POST["password"]))
        $errors["password"] = "Password required";
    if(isset($_POST["password"]) && $_POST["password"] !== $_POST["passwordConf"])
        $errors["passwordConf"] = "Passwords do not match";
    $valid_email = check_email();
    if($valid_email !== true)
        $errors["domain"] = "Email is not a(n) " .$valid_email. " email address";

    $universityID = $_POST["universityID"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $token = bin2hex(random_bytes(50));
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    //Check if email is already in use
    $sql = "SELECT * FROM gib_users WHERE email='$email' LIMIT 1";
    $conn = new gibConnection($sql);
    if($conn->num_rows > 0)
        $errors["email"] = "Email already exists";

    //Create user
    if(count($errors) === 0){
        //$query = "INSERT INTO gib_users SET university_id=?, first_name=?, last_name=?,
        //                                    email=?, username=?, token=?, password=?";
        $query = 'INSERT INTO gib_users SET university_id='.$universityID.', first_name="'.$firstName.'", last_name="'.$lastName.'",
                                            email="'.$email.'", username="'.$username.'",
                                            token="'.$token.'", password="'.$password.'"';
        //$conn = new gibConnection($query,true);
        //$conn->bind_stmt->bind_param('issssss',$universityID,$firstName,
        //                                $lastName, $email, $username, $token, $password);
        //$result = $conn->bind_stmt->execute();
        $conn = new gibConnection($query);

        if($conn->success){
            $conn = new gibConnection('SELECT user_id FROM gib_users WHERE token="'.$token.'"');
            $user_id = $conn->result[0]['user_id'];

            //Send verification email
            $message = '<p>Thank you for signing up for gibbl.io. Please click the link below to verify your account.</p>';
            $message .= '<a href="https://gibbl.io/user/verify_email.php?token='.$token.'">Verify Email!</a>';
            $outgoingMail = new gibEmail('Verify your email',$message,Array($email));
            $sent = $outgoingMail->send();

            $_SESSION["id"] = $user_id;
            $_SESSION["universityID"] = $universityID;
            $_SESSION["firstName"] = $firstName;
            $_SESSION["lastName"] = $lastName;
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $username;
            $_SESSION["verified"] = false;
            $_SESSION["securityLevel"] = 1;
            if($sent)
                $_SESSION["message"] = "You are logged in! You must verify your email <strong>".$_SESSION["email"]."</strong>.";
            else
                $_SESSION["message"] = "Email failed to send. You are still logged in";
            $_SESSION["type"] = "alert-success";
            header("location: https://gibbl.io");
            exit();
        }
        else{
            $_SESSION["error_msg"] = "Database error: Could not register user";
        }
    }
}


//Login
if(isset($_POST["login-btn"])){
    if(empty($_POST["email"]))
        $errors["email"] = "Email required";
    if(empty($_POST["password"]))
        $errors["password"] = "Password required";
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(count($errors) === 0){
        $query = 'SELECT * FROM gib_users WHERE email="'.$email.'" LIMIT 1';
        $conn = new gibConnection($query);

        if($conn->success){
            $user = $conn->result[0];

            //Check if password matches
            if(password_verify($password, $user["password"])){

                $_SESSION["id"] = $user["user_id"];
                $_SESSION["universityID"] = $user["university_id"];
                $_SESSION["firstName"] = $user["first_name"];
                $_SESSION["lastName"] = $user["last_name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["verified"] = $user["verified"];
                $_SESSION["securityLevel"] = $user["security_level"];
                $_SESSION["message"] = "You are logged in!";
                $_SESSION["type"] = "alert-success";
                header("location: https://gibbl.io");
                exit();
            }
            else{
                $errors["login_fail"] = "Wrong username / password";
            }
        }
        else{
            $_SESSION["message"] = "Database error. Login failed!";
            $_SESSION["type"] = "alert-danger";
        }
    }
}


//Function to write alerts
function write_alerts($errors): string
{
    $str = '';
    if(count($errors) > 0) {
        $str = '<div class="alert alert-danger">';
        foreach ($errors as $error)
            $str .= '<li>' . $error . '</li>';
        $str .= '</div>';
    }
    return $str;
}

//Function to check for valid signup email
function check_email(){
    if(isset($_POST["email"]) && isset($_POST["universityID"])){
        $email = $_POST["email"];
        $domain = substr($email,strpos($email,"@"));
        $con = new gibConnection('SELECT * FROM university_info WHERE ID='.$_POST["universityID"].' LIMIT 1');
        if($con->result[0]["Domain"] != $domain)
            return $con->result[0]["School"];
    }
    return true;
}