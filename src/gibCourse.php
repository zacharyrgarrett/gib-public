<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");

class gibCourse
{
    public $id;
    public $code;
    public $name;
    public $professor;
    public $semester;

    public function __construct($SchoolID,$CourseCode)
    {
        $db = gibUniversity::get_university_db($SchoolID);
        $query = 'SELECT * FROM courses WHERE course_code="'.$CourseCode.'" LIMIT 1';
        $con = new gibConnection($query, $db);

        if($con->num_rows){
            $result = $con->result[0];
            $this->id = $result["course_code"];
            $this->code = $result["course"];
            $this->name = $result["descr"];
            $this->professor = $result["professor"];
            $this->semester = $result["semester"];
        }
    }
}