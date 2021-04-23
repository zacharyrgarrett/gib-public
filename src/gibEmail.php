<?php

require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
use PHPMailer\PHPMailer\PHPMailer;

date_default_timezone_set('America/Phoenix');

class gibEmail
{
    private $subject;
    private $recipients;
    private $message;
    private $mail;

    public function __construct($subject='',$message='',$recipients=Array())
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->recipients = $recipients;

        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = 4;
        $this->mail->Host = 'HOST';
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        $this->mail->Username = 'EMAIL';
        $this->mail->Password = 'PASSWORD';
    }

    public function set_subject($subject){
        $this->subject = $subject;
    }

    public function set_message($message){
        $this->message = $message;
    }

    public function set_recipients($recipients){
        $this->recipients = $recipients;
    }

    public function send(){

        $this->mail->setFrom('admin@gibbl.io','gibbl.io');
        foreach($this->recipients as $recipient)
            $this->mail->addAddress($recipient);
        $this->mail->Subject = $this->subject;
        $this->mail->isHTML(true);
        $this->mail->Body = $this->write_message();
        return $this->mail->send();
    }

    private function write_message(){
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';

        //Write head
        $html .= '<head>';
        $html .=    '<meta charset="UTF-8">';
        $html .=    '<title>gibbl.io</title>';
        $html .=    '<link rel="stylesheet" href="/css/mail.css?v=1.0">';
        $html .= '</head>';

        //Write body
        $html .= '<body>';
        $html .=    '<div class="wrapper">';
        $html .=        $this->message;
        $html .=    '</div>';
        $html .= '</body>';

        $html .= '</html>';

        return $html;
    }
}