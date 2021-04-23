<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
include($_SERVER['DOCUMENT_ROOT']."/controllers/authController.php");

$thisPage = new gibPage("Signup",false);

$form = '<h1>Signup</h1>';
$form .= write_alerts($errors); //errors array from authController.php
$form .= '<form class="form-horizontal" action="https://gibbl.io/user/signup.php" method="post">';

//School
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="universityID">School:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            write_school_options();
$form .=        '</div>';
$form .=    '</div>';

//First Name
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="firstName">First Name:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name">';
$form .=        '</div>';
$form .=    '</div>';

//Last Name
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="lastName">Last Name:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name">';
$form .=        '</div>';
$form .=    '</div>';

//Email
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="email">Email:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">';
$form .=        '</div>';
$form .=    '</div>';

//Username
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="username">Username:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">';
$form .=        '</div>';
$form .=    '</div>';

//Password
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="password">Password:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">';
$form .=        '</div>';
$form .=    '</div>';

//Password Confirmation
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="passwordConf">Confirm Password:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="password" class="form-control" id="passwordConf" name="passwordConf" placeholder="Confirm password">';
$form .=        '</div>';
$form .=    '</div>';

//Submit
$form .=    '<div class="form-group">';
$form .=        '<div class="col-sm-offset-2 col-sm-10">';
$form .=            '<button type="submit" name="signup-btn" class="btn btn-lg btn-block">Submit</button>';
$form .=        '</div>';
$form .=    '</div>';

$form .= '</form>';

$thisPage->add_content($form);

//$image = '<img src="/images/stonks.jpg">';
//$thisPage->add_content($image);

$thisPage->write_page();


function write_school_options(): string
{
    $query = 'SELECT * FROM university_info';
    $con = new gibConnection($query);
    $str = '<select class="form-control" id="universityID" name="universityID">';
    $str .= '<option value="" selected disabled hidden>Choose school</option>';
    foreach($con->result as $row) {
        if ($row["Active"]) {
            $str .= '<option value="' . $row["ID"] . '">' . $row["School"] . '</option>';
        }
    }
    $str .= '</select>';
    return $str;
}