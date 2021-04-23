<?php

//Require
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConfiguration.php");

class gibNav
{
    private $signed_in;
    private $nav_elements;
    public $side_nav_elements;

    public function __construct($signed_in, $nav_elements = NULL)
    {
        if($nav_elements)
            $this->nav_elements = $nav_elements;
        else
            $this->nav_elements = gibConfiguration::$nav_elements;

        $this->side_nav_elements = Array("Left"=>Array(), "Right"=>Array());
        $this->signed_in = $signed_in;
    }

    private function write_nav_elements(){
        $html = '';
        foreach ($this->nav_elements as $key=>$element){
            if(is_array($element))
                $html .= $this->write_dropdown_element($key,$element);
            else
                $html .= '<li><a href="'.gibConfiguration::$URL.$element.'">'.$key.'</a></li>';
        }
        return $html;
    }

    private function write_dropdown_element($key,$elements){
        $html = '<li class="dropdown">';
        $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$key;
        $html .= '<span class="caret"></span></a>';
        $html .= '<ul class="dropdown-menu">';

        foreach ($elements as $name=>$element){
            if(is_array($element))
                $html .= $this->write_dropdown_element($name,$element);
            else
                $html .= '<li><a href="'.$element.'">'.$name.'</a></li>';
        }
        $html .= '</ul>';
        $html .= '<li>';
        return $html;
    }

    public function write_main_nav(){
        $nav =  '<nav class="navbar navbar-inverse">';
        $nav .=     '<div class="container-fluid">';
        $nav .=         '<div class="navbar-header">';
        $nav .=             '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">';
                                foreach($this->nav_elements as $element)
        $nav .=                     '<span class="icon-bar"></span>';
        $nav .=             '</button>';
        $nav .=             '<a class="navbar-brand" href="'.gibConfiguration::$URL.'" style="margin: auto"><img src="../images/logo1.png" height="30" alt="Logo"></a>';
        $nav .=         '</div>';
        $nav .=         '<div class="collapse navbar-collapse" id="myNavbar">';
        $nav .=             '<ul class="nav navbar-nav">';
        $nav .=                 $this->write_nav_elements();
        $nav .=             '</ul>';
        $nav .=             '<ul class="nav navbar-nav navbar-right">';
        $nav .=                 $this->write_user_controls();
        $nav .=             '</ul>';
        $nav .=         '</div>';
        $nav .=     '</div>';
        $nav .= '</nav>';

        return $nav;
    }

    private function write_user_controls(){
        $html = '';
        if($this->signed_in){
            $html .= '<li><a href="../user/account.php">'.$_SESSION["firstName"].' '.$_SESSION["lastName"].'</a></li>';
            $html .= '<li><a href="../user/logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>';
        }
        else{
            $html .= '<li><a href="../user/signup.php">Signup</a></li>';
            $html .= '<li><a href="../user/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        }
        return $html;
    }

    public function write_side_nav($Loc){
        $side_nav = '<div class="col-sm-2 sidenav">';
            foreach($this->side_nav_elements[$Loc] as $element)
                $side_nav .= '<div class="navelem">'.$element.'</div>';
        $side_nav .= '</div>';

        return $side_nav;
    }
}