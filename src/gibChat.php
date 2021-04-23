<?php

require_once($_SERVER['DOCUMENT_ROOT']."/src/gibCourse.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUser.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibConnection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/gibUniversity.php");

class gibChat
{
    private $Course;

    public function __construct($SchoolID, $CourseCode)
    {
        $this->Course = new gibCourse($SchoolID,$CourseCode);
    }

    private function add_scripts(){
        $script = '
            <script>
                var from = null, start = 0, url = "https://gibbl.io/controllers/submit_chat.php";
                $(document).ready(function(){
                    from = "'.$_SESSION['id'].'";
                    load();
                    $("form").submit(function (e) {
                        $.post(url, {
                            message: $("#message").val(),
                            from: from,
                            course: "'.$this->Course->id.'"
                        });
                        $("#message").val("");
                        return false;
                    })
                });
                
                function load(){
                    $.get(url + "?start=" + start + "&course='.$this->Course->id.'", function(result){
                        if(result.items){
                            result.items.forEach(item => {
                                start = item.chat_id;
                                $("#messages").append(renderMessage(item));
                            });
                            $("#messages").animate({scrollTop: $("#messages")[0].scrollHeight});
                        };
                        load();
                    });
                }
                
                function renderMessage(item){
                    let time = new Date(item.created);
                    time = `${time.getHours()}:${time.getMinutes() < 10 ? "0" : ""}${time.getMinutes()}`;
                    msg_class = `${item.username}` == "'.$_SESSION["username"].'" ? "usermsg" : "othermsg";
                    return `<div class="msg ${msg_class}"><p>${item.username}</p>${item.message}<span>${time}</span></div>`;
                }
            </script>
        ';

        return $script;
    }

    public function write_chat(){
        $html = '<link rel="stylesheet" href="/css/chat.css">';
        $html .= $this->add_scripts();
        $html .= '<h1>'.$this->Course->code.' - '.$this->Course->name.' ('.$this->Course->professor.')</h1>';
        $html .= '<div class="chat">';
        $html .= '<div id="messages"></div>';
        $html .= '<form>';
        $html .=    '<input type="text" id="message" autocomplete="off" autofocus placeholder="Type message...">';
        $html .=    '<input type="submit" value="Send">';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }


    static public function get_my_chats($panel = true, $user = false){
        if(!$user)
            $user = gibUser::get_user_id();
        $query = 'SELECT course_code FROM saved_courses WHERE user_id='.$user;
        $db = gibUniversity::get_university_db();
        $con = new gibConnection($query, $db);

        if($con->num_rows){
            $saved = Array();
            foreach ($con->result as $row)
                $saved[] = $row["course_code"];
            $query = 'SELECT * FROM courses WHERE course_code IN ("'.implode('","',$saved).'")';
            $con = new gibConnection($query, $db);
            if($con->num_rows)
                return $con->result;
        }
        return Array();
    }


    static public function get_saved_chat_panel(){
        $saved = self::get_my_chats();
        $html = '<ul>';
        if(sizeof($saved) > 0){
            foreach($saved as $row)
                $html .= '<li><a href="?course='.$row["course_code"].'">'.$row["course"]." (".$row["professor"].')</a>'.'</li>';
        }
        else{
            $html .= '<li><i>You have no saved chats</i></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}