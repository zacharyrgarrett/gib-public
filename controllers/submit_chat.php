<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibSecurity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");

gibSecurity::permission_level(1);

//$_POST['course'] = 23;

$result = array();
$message = isset($_POST['message']) ? $_POST['message'] : null;
$from = isset($_POST['from']) ? $_POST['from'] : null;
$course = isset($_POST['course']) ? $_POST['course'] : null;
$db = gibUniversity::get_university_db($_SESSION["universityID"]);

if(!empty($message) && !empty($from)){
    $sql = 'INSERT INTO chats (message,sender_id,course_code) VALUES ("'.$message.'","'.$from.'","'.$course.'")';
    $con = new gibConnection($sql,$db);
    $result['send_status'] = $con->success;
}

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$course = isset($_GET['course']) ? $_GET['course'] : 0;
$query = 'SELECT * FROM chats WHERE chat_id > '.$start.' && course_code = "'.$course.'"';
$con = new gibConnection($query,$db);
foreach($con->result as $row){
    $row["username"] = gibUser::get_username($row['sender_id']);
    $result['items'][] = $row;
}


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode($result);