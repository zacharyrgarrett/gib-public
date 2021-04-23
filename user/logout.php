<?php
session_start();

unset($_SESSION["id"]);
unset($_SESSION["universityID"]);
unset($_SESSION["firstName"]);
unset($_SESSION["lastName"]);
unset($_SESSION["email"]);
unset($_SESSION["username"]);
unset($_SESSION["verified"]);
unset($_SESSION["securityLevel"]);

$_SESSION["message"] = "You have been logged out.";
$_SESSION["type"] = "alert-success";

header("location: https://gibbl.io/user/login.php");
exit();