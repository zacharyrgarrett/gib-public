<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibEmail.php");

$thisPage = new gibPage("gibbl.io");

$content = '<div class="jumbotron text-center">';
$content .= '<h2>Welcome to Gibbl</h2>';
$content .= '<h4>We provide group chats for every class offered at your school.
             <a href="https://gibbl.io/user/signup.php">Sign up</a> or <a href="https://gibbl.io/user/login.php">login</a> to begin.</h4>';
//$content .= '<form><label>Input:</label><br><input type="text" id="name"><input type="button" value="Submit"></form>';
$content .= '</div>';
$content .= '<img style="width: 90%; display: block; margin-left: auto; margin-right: auto;" src="/images/example_chat.png">';

//$message = '<p>Somone has visited gibbl.io</p>';
//$email = new gibEmail("Someone visited gib",$message,Array("zacharyrgarrett@gmail.com"));
//$email->send();

$thisPage->add_content($content);
$thisPage->write_page();