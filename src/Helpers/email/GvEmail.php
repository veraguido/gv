<?php

namespace Gvera\Helpers\email\GvEmail;

use Gvera\Helpers\config\Config;

/**
 * Class GVEmail
 * @package Gvera\Helpers\email\GVEmail
 * This class is a wrapper from the PhpMailer class just for easiness purposes.
 */
class GvEmail {

    private $mailer;
    private $host;
    private $SMTPAuth = true;
    private $username;
    private $password;
    private $SMTPSecure;
    private $port;

    public function __construct($isHtml, $subject, $body, $alternativeBody)
    {
        $config = Config::getInstance()->getConfig('email');
        $this->mailer = new \PHPMailer();
        $this->mailer->isHTML($isHtml);
        $this->host = $config['host'];
        $this->SMTPAuth = (bool)$config['smtp_auth'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->SMTPSecure = $config['smtp_secure'];
        $this->port = $config['port'];

        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = $alternativeBody;
    }

    public function addAddress($address, $name = '') {
        $this->mailer->addAddress($address, $name);
    }

    public function addCC($cc, $name = '') {
        $this->mailer->addCC($cc, $name);
    }

    public function addBcc($bcc, $name = '') {
        $this->mailer->addBCC($bcc, $name);
    }

    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment') {
        $this->mailer->addAttachment($path, $name, $encoding, $type, $disposition);
    }

    public function send(){
        if(!$this->mailer->send()) {
            throw new \Exception('Message could not be sent ' . $this->mailer->ErrorInfo);
        } else {
            //your implementation here
        }
    }

}