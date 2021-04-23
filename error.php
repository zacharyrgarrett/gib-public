<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");

$thisPage = new gibPage("Error");
$html = '';

if(isset($_GET['type'])){
    $err_type = $_GET['type'];

    switch($err_type){
        case 1:
            $html .= '<p>You are not authorized for this content.</p>';
            $html .= '<p>Make sure you are <a href="https://gibbl.io/user/login.php"><strong>signed in</strong></a></p>';
    }
}
else{
    $html .= '<p>There is no error.</p>';
}

$thisPage->add_content($html);
$thisPage->write_page();