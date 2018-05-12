<?php
namespace Gvera\Helpers\email;

use Gvera\Helpers\config\Config;

/**
 * Class GVEmail
 * @package Gvera\Helpers\email\GVEmail
 * This class is a wrapper from the PhpMailer class just for easiness purposes.
 * @Inject config
 */
class GvEmail
{
    private $mailer;

    public function __construct($isHtml, $subject, $body, $alternativeBody)
    {
        $emailConfig = $this->config->getConfig('email');
        $this->mailer = new \PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->isHTML($isHtml);
        $this->mailer->Host = $emailConfig['host'];
        $this->mailer->SMTPAuth = (bool)$emailConfig['smtp_auth'];
        $this->mailer->From = $emailConfig['username'];
        $this->mailer->Password = $emailConfig['password'];
        $this->mailer->SMTPSecure = $emailConfig['smtp_secure'];
        $this->mailer->Port = $emailConfig['port'];

        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = $alternativeBody;
    }

    public function addAddress($address, $name = '')
    {
        $this->mailer->addAddress($address, $name);
    }

    public function addCC($cCopy, $name = '')
    {
        $this->mailer->addCC($cCopy, $name);
    }

    public function addBcc($bcCopy, $name = '')
    {
        $this->mailer->addBCC($bcCopy, $name);
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
