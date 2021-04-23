<?php

require_once($_SERVER['DOCUMENT_ROOT']."/user/update_user.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");

$thisPage = new gibPage("Account Info",true);
$thisUser = new gibUser();

$form = '<h1>Account Information</h1><br>';
$form .= '<form class="form-horizontal" action="https://gibbl.io/user/account.php" method="post">';

//School
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="school">School:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="school" name="school" value="'.$thisUser->get_university_name().' ('.$thisUser->get_university_abbreviation().')" readonly>';
$form .=        '</div>';
$form .=    '</div>';

//First Name
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="firstName">First Name:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="firstName" name="firstName" value="'.$thisUser->first_name.'">';
$form .=        '</div>';
$form .=    '</div>';

//Last Name
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="lastName">Last Name:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="lastName" name="lastName" value="'.$thisUser->last_name.'">';
$form .=        '</div>';
$form .=    '</div>';

//Email
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="email">Email:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="email" class="form-control" id="email" name="email" value="'.$thisUser->email.'" readonly>';
$form .=        '</div>';
$form .=    '</div>';

//Username
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="username">Username:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="text" class="form-control" id="username" name="username" value="'.$thisUser->username.'">';
$form .=        '</div>';
$form .=    '</div>';

//Submit
$form .=    '<div class="form-group">';
$form .=        '<div class="col-sm-offset-2 col-sm-10">';
$form .=            '<button type="submit" name="info-update-btn" class="btn btn-lg btn-block">Update Info</button>';
$form .=        '</div>';
$form .=    '</div>';

$form .= '</form>';

$form .= '<br>';

$form .= '<form class="form-horizontal" action="https://gibbl.io/user/account.php" method="post">';

//Update Password
$form .= '<h1>Change Password</h1><br>';
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="password">New Password:</label>';
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
$form .=            '<button type="submit" name="new-pw-btn" class="btn btn-lg btn-block">Update Password</button>';
$form .=        '</div>';
$form .=    '</div>';

$form .= '</form>';

$thisPage->add_content($form);
$thisPage->write_page();