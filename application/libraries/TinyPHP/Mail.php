<?php
namespace Libraries\TinyPHP;

use \PHPMailer;
use \Exception;

class Mail
{
    private $recipients = array();
    private $from = '';
    private $fromDisplay = '';
    private $type = 'text/html';
    private $subject;
    private $body;
    private $attachments = array();
    private $transportType = 'smtp';
    private $replyTo;

    public function addRecipient($recipientEmail)
    {
        $this->recipients[] = $recipientEmail;
    }

    public function setFrom($fromEmail, $displayName = null)
    {
        $this->from = $fromEmail;
        if($displayName){
            $this->fromDisplay = $displayName;
        }
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function addAttachment($attachmentPath)
    {
        $this->attachments[] = $attachmentPath;
    }

    public function setTransportType($transportType)
    {
        $this->transportType = $transportType;
    }
    
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    public function send()
    {
        $mail = new PHPMailer();
        $mail->SetFrom($this->from, $this->fromDisplay);
        if(empty($this->recipients)){
            throw new Exception("Please add at least 1 recipient!");
        }
        foreach($this->recipients as $recipientEmail){
            $mail->AddAddress($recipientEmail);
        }
        $mail->Subject = $this->subject;
        $mail->MsgHTML($this->body);
        
        if($this->transportType == 'smtp'){
            $mail->SMTPAuth = true;
            $mail->isSMTP();
            $config = Application::$config;
            $mail->Host =  $config['smtpHost'];
            $mail->Port = $config['smtpPort'];
            $mail->Username = $config['smtpUsername'];
            $mail->Password = $config['smtpPassword'];
        }
        
        if(!empty($this->attachments)){
            foreach($this->attachments as $attachmentPath){
                $mail->AddAttachment($attachmentPath);
            }
        }
        
        if($this->replyTo){
            $mail->addReplyTo($this->replyTo);
        }
        
        if(!$mail->Send()){
            throw new Exception("Mailer Error: " . $mail->ErrorInfo);
        }
    }
}