<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibChat.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");

gibSecurity::permission_level(1);

$page = new gibPage("gib Chat");
$page->set_left_nav(Array('<a href="browse.php">Browse ASU</a>','<a href="saved.php">Saved Chats</a>'.gibChat::get_saved_chat_panel()));
$page->set_right_nav(Array('<a href="#">Pinned Chats</a>','<a href="#">Recent Files</a>'));

if(isset($_GET["course"])){
    $chat = new gibChat(gibUniversity::get_session_university_id(),$_GET["course"]);
    $page->add_content($chat->write_chat());
}
else{
    $html = '<h1>Welcome to '.gibUniversity::get_university_abbrev().' Chats!';
    $html .= '<h4>To get started, <a href="browse.php"><strong>browse</strong></a> or select one of your saved chats.';
    $page->add_content($html);
}


$page->write_page();