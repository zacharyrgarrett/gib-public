<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");

$thisPage = new gibPage("About");

$html = '<h1>About gibbl.io</h1>';
$html .= '<p>This website was created to serve as a platform for seamless communication among students.</p>';
$html .= '<p>This will help bridge the distance created by the COVID-19 pandemic.</p>';
$thisPage->add_content($html);
$thisPage->write_page();