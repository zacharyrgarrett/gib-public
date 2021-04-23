<?php
session_start();

//Require
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConfiguration.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibNav.php");

class gibPage
{
    private $Title;
    private $Nav;
    private $Signed_In = false;
    private $Verified = false;
    private $Content = Array();
    private $Scripts = Array();
    private $Styles = Array();
    public $Get = Array();
    public $Post = Array();

    public function __construct($title,$check_access=false)
    {
        $this->check_user_status();
        if($check_access)
            $this->check_access();
        $this->Title = $title;
        $this->Nav = new gibNav($this->Signed_In);
        $this->include_essential_files();
        $this->add_alerts();
        $this->get_requests();
    }

    private function include_essential_files(){
        $this->add_style(gibConfiguration::$bootstrap_css, true);
        $this->add_style("page.css");
        $this->add_script(gibConfiguration::$jQuery_js, true);
        $this->add_script(gibConfiguration::$bootstrap_js, true);
    }

    private function add_alerts(){
        $alerts = "";
        if(isset($_SESSION["message"])){
            $alerts .= '<div class="alert '.$_SESSION["type"].' text-center">';
            $alerts .= $_SESSION["message"];
            unset($_SESSION["message"]);
            unset($_SESSION["type"]);
            $alerts .= '</div>';
        }
        $this->add_content($alerts);
    }

    public function add_content($html){
        $this->Content[] = '<div>'.$html.'</div>';
    }

    public function add_script($script_path, $link = false){
        $source = $link ? '' : 'https://gibbl.io/js/';
        $this->Scripts[] = '<script src="'.$source.$script_path.'"></script>';
    }

    public function add_style($style_path, $link = false){
        $source = $link ? '' : 'https://gibbl.io/css/';
        $this->Styles[] = '<link rel="stylesheet" href="'.$source.$style_path.'?v=1.0">';
    }

    public function disable_left_nav(){
        $this->Nav->side_nav_elements["Left"] = Array();
    }

    public function disable_right_nav(){
        $this->Nav->side_nav_elements["Right"] = Array();
    }

    public function set_left_nav($elements){
        $this->Nav->side_nav_elements["Left"] = $elements;
    }

    public function set_right_nav($elements){
        $this->Nav->side_nav_elements["Right"] = $elements;
    }

    private function check_user_status(){
        if(!empty($_SESSION['id']))
            $this->Signed_In = true;

        if(!empty($_SESSION["verified"]) && $_SESSION["verified"])
            $this->Verified = true;
    }

    private function check_access(){
        if(!$this->Signed_In) {
            $_SESSION["message"] = "You must first login";
            $_SESSION["type"] = "alert-warning";
            header('location: https://gibbl.io/user/login.php');
            exit();
        }
        if(!$this->Verified){
            $_SESSION["message"] = "You must first verify your email address (<strong>".$_SESSION["email"]."</strong>)";
            $_SESSION["type"] = "alert-warning";
            header('location: https://gibbl.io');
            exit();
        }
    }

    private function get_requests(){
        if(isset($_GET))
            $this->Get = $_GET;
        if(isset($_POST))
            $this->Post = $_POST;
    }

    public function get($index){
        if(isset($this->Get[$index]))
            return $this->Get[$index];
        else
            return false;
    }

    public function post($index){
        if(isset($this->Post[$index]))
            return $this->Post[$index];
        else
            return false;
    }

    public function write_page(){

        //Start html
        $page = '<!doctype html>';
        $page .= '<html lang="en">';

        //Write head and add style
        $page .= '<head>';
        $page .= '<meta charset="utf-8">';
        $page .= '<title>'.$this->Title.'</title>';
        $page .= '<meta name="description" content="University Chat Rooms">';
        $page .= '<meta name="author" content="Gibbl">';
        $page .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $page .= implode("",$this->Styles);
        $page .= '<link rel="icon" type="image/png" href="https://gibbl.io/images/favicon.png">';
        $page .= '</head>';

        //Write body
        $page .= '<body>';
        $page .=    $this->Nav->write_main_nav();
        $page .=    '<div class="container-fluid text-center">';
        $page .=        '<div class="row content">';
        $page .=            $this->Nav->write_side_nav("Left");
        $page .=            '<div class="col-sm-8 text-left" style="padding: 25px">';
        $page .=                implode("",$this->Scripts);
        $page .=                implode("",$this->Content);
        $page .=            '</div>';
        $page .=            $this->Nav->write_side_nav("Right");
        $page .=        '</div>';
        $page .=    '</div>';
        $page .= '<footer class="container-fluid text-center">';
        //$page .= '<p>Footer Text</p>';
        $page .= '</footer>';
        $page .= '</body>';

        //Finish html
        $page .= '</html>';

        //Write page
        echo($page);
    }

}