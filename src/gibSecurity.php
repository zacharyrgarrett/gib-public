<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");

define("NONE",0);
define("STUDENT",1);
define("ADMIN",2);

class gibSecurity
{
    /**
     * @param $level int
     * indicates security level
     * 0 (NONE) -> Anyone can view the page
     * 1 (STUDENT) -> All email verified users can view the page
     * 2 (ADMIN) -> Only admin access
     */
    public static function permission_level(int $level){
        $thisUser = new gibUser();
        $access = false;

        switch($level){
            //Anyone
            case 0:
                $access = true;
                break;

            //Student
            case 1:
                if($thisUser->security_level == STUDENT || $thisUser->security_level == ADMIN)
                    $access = true;
                break;

            //Admin
            case 2:
                if($thisUser->security_level == ADMIN)
                    $access = true;
                break;
        }

        if(!$access){
            header("Location: https://gibbl.io/error.php?type=1");
            exit();
        }
    }
}