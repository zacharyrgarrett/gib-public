<?php

/**
 * Class gibConfiguration
 * Author: Zachary Garrett
 * Date: 12/17/2020
 * Modified: 12/17/2020
 */

class gibConfiguration
{
    //Site Info
    static $URL = "https://gibbl.io/";
    static $ROOT = "GIB_PROJECT_ROOT";

    //JS and Bootstrap links
    static $bootstrap_css = "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css";
    static $bootstrap_js = "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js";
    static $jQuery_js = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js";

    //Nav Bar
    static $nav_elements =
        Array(
            "Home" => "",
            "About" => "about.php",
            "Chat" => "chat"
        );

    //phpMyAdmin
    static $db_server = "localhost";
    static $db_username = "DB_USERNAME";
    static $db_password = "DB_PASSWORD";
}
