<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
include($_SERVER['DOCUMENT_ROOT']."/controllers/authController.php");

$thisPage = new gibPage("Login");

$form = '<h1>Login</h1>';
$form .= write_alerts($errors); //errors array from authController.php
$form .= '<form class="form-horizontal" action="https://gibbl.io/user/login.php" method="post">';

//Email
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="email">Email:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">';
$form .=        '</div>';
$form .=    '</div>';

//Password
$form .=    '<div class="form-group">';
$form .=        '<label class="control-label col-sm-2" for="password">Password:</label>';
$form .=        '<div class="col-sm-10">';
$form .=            '<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">';
$form .=        '</div>';
$form .=    '</div>';

//Submit
$form .=    '<div class="form-group">';
$form .=        '<div class="col-sm-offset-2 col-sm-10">';
$form .=            '<button type="submit" value="Submit" name="login-btn" class="btn btn-lg btn-block">Login</button>';
$form .=        '</div>';
$form .=    '</div>';

$form .= '</form>';

$thisPage->add_content($form);
$thisPage->write_page();