<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibChat.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");

gibSecurity::permission_level(1);

$page = new gibPage("gibbl.io - Saved");
$page->add_style("class_list.css");
$page->add_script("toggle_save_chat.js");
$page->set_left_nav(Array('<a href="browse.php">Browse ASU</a>','<a href="saved.php">Saved Chats</a>'.gibChat::get_saved_chat_panel()));
//$page->set_right_nav(Array('<a href="#">Pinned Chats</a>','<a href="#">Recent Files</a>'));

$saved = gibChat::get_my_chats();

//Display courses
$list = '<form><div class="list"><ul>';

if(sizeof($saved) > 0)
    foreach ($saved as $row){
        $icon = "Remove";
        $list .= '<li>';
        $list .= $row["course"].' - '.$row["descr"].' ('.$row["professor"].')';
        $list .= '<button type="submit" value="'.$row["course_code"].'" name="toggle_class">'.$icon.'</button>';
        $list .= '</li>';
    }
else
    $list .= '<li><i>You have no saved chats. <a href="browse.php"><strong>Browse</strong></a></i></li>';

$list .= '</ul></div></form>';

$page->add_content('<h1>Saved Chats</h1><br>');
$page->add_content($list);

//Show page
$page->write_page();