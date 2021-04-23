<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibPage.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibChat.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");

gibSecurity::permission_level(1);

$page = new gibPage("gibbl.io - Explore");
$page->add_style("class_list.css");
$page->add_script("toggle_save_chat.js");
$db = gibUniversity::get_university_db();

//List of classes saved by specified user
$course_ids = Array();
$query = 'SELECT * FROM saved_courses WHERE user_id='.gibUser::get_user_id();
$con = new gibConnection($query, $db);
foreach($con->result as $row)
    $course_ids[] = $row["course_code"];

//Filter based on form
$semester = $page->get("semester");
$subject = $page->get("subject");
$number = $page->get("number");

$parameters = Array();
if($semester !== false)
    $parameters[] = 'semester="'.$semester.'"';
if(($subject !== false) || ($number !== false)){
    $parameters[] = "course LIKE '".$subject."% %".$number."'";
}
$where = $semester || $subject || $number ? " WHERE " . implode(" AND ",$parameters) : "";


//List of classes from specified university
$class_results = Array();
if($where !== ""){
    $query = 'SELECT * FROM courses'.$where;
    $con = new gibConnection($query, $db);
    $class_results = $con->result;
}

$semester_options = Array("FA21","SU21","SP21");

//Search bar
$search_bar =   '<form method="get" action="browse.php">';
$search_bar .=   '<div class="gen_margin">';
$search_bar .=   '<div class="form-group">';
$search_bar .=      '<label class="control-label col-sm-2" for="semester">Semester:</label>';
$search_bar .=      '<select class="form-control" id="semester" name="semester" style="font-size: small; width: auto; height: auto;">';

                    foreach ($semester_options as $option){
                        $selected = $semester == $option ? 'selected' : '';
                        $search_bar .= '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';}

$search_bar .=      '</select>';
$search_bar .=  '</div>';

$search_bar .=    '<div class="form-group">';
$search_bar .=        '<label class="control-label col-sm-2" for="subject">Subject:</label>';
$search_bar .=        '<div class="col-sm-10">';
$search_bar .=            '<input type="text" class="form-control" id="subject" name="subject" value="'.$subject.'" placeholder="Ex: CSE, IEE, MAT . . ." style="font-size: small; width: auto; height: auto;">';
$search_bar .=        '</div>';
$search_bar .=    '</div>';

$search_bar .=    '<div class="form-group">';
$search_bar .=        '<label class="control-label col-sm-2" for="number">Number:</label>';
$search_bar .=        '<div class="col-sm-10">';
$search_bar .=            '<input type="text" class="form-control" id="number" name="number" value="'.$number.'" placeholder="Ex: 310, 330, 355 . . ." style="font-size: small; width: auto; height: auto;">';
$search_bar .=        '</div>';
$search_bar .=    '</div>';

$search_bar .=    '<div class="form-group">';
$search_bar .=        '<div class="col-sm-2"></div>';
$search_bar .=        '<div class="col-sm-10">';
$search_bar .=            '<input type="submit" class="form-control" id="search" name="search" value="Search" style="width: auto">';
$search_bar .=        '</div>';
$search_bar .=    '</div>';
$search_bar .=    '</div>';
$search_bar .=    '</form>';

$page->add_content($search_bar);

//Display courses
$list = '<form><div class="list"><ul>';
foreach ($class_results as $row){
    $icon = in_array($row["course_code"],$course_ids) ? "Remove" : "Add";
    $list .= '<li>';
    $list .= '<i style="color: #555555; margin-right: 20px">'.$row["semester"].'</i>'.$row["course"].' - '.$row["descr"].' ('.$row["professor"].')';
    $list .= '<button type="submit" value="'.$row["course_code"].'" name="toggle_class">'.$icon.'</button>';
    $list .= '</li>';
}
$list .= '</ul></div></form>';

$page->add_content($list);

//Show page
$page->write_page();