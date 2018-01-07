<?php
namespace Gvera\Helpers\email;

use Gvera\Helpers\config\Config;

/**
 * Class GVEmail
 * @package Gvera\Helpers\email\GVEmail
 * This class is a wrapper from the PhpMailer class just for easiness purposes.
 */
class GvEmail
{
    private $mailer;

    public function __construct($isHtml, $subject, $body, $alternativeBody)
    {
        $config = Config::getInstance()->getConfig('email');
        $this->mailer = new \PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->isHTML($isHtml);
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = (bool)$config['smtp_auth'];
        $this->mailer->From = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = $config['smtp_secure'];
        $this->mailer->Port = $config['port'];

        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = $alternativeBody;
    }

    public function addAddress($address, $name = '')
    {
        $this->mailer->addAddress($address, $name);
    }

    public function addCC($cc, $name = '')
    {
        $this->mailer->addCC($cc, $name);
    }

    public function addBcc($bcc, $name = '')
    {
        $this->mailer->addBCC($bcc, $name);
    }

    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
    {
        $this->mailer->addAttachment($path, $name, $encoding, $type, $disposition);
    }

    /**
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function send()
    {
        if (!$this->mailer->send()) {
            throw new \Exception('Message could not be sent ' . $this->mailer->ErrorInfo);
        } else {
            //your implementation here
        }
    }
}
