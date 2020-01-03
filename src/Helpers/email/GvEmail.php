<?php
namespace Gvera\Helpers\email;

/**
 * Class GVEmail
 * @package Gvera\Helpers\email\GVEmail
 * This class is a wrapper from the PhpMailer class just for easiness purposes.
 * @Inject config
 */
class GvEmail
{
    private $mailer;

    private $isHtml = false;
    private $subject;
    private $body;
    private $alternativeBody;
    public $config;


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

        $this->validate();

        $emailConfig = $this->getEmailConfiguration();
        $this->mailer = new \PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->isHTML($this->isHtml);
        $this->mailer->Host = $emailConfig['host'];
        $this->mailer->SMTPAuth = (bool)$emailConfig['smtp_auth'];
        $this->mailer->From = $emailConfig['username'];
        $this->mailer->Password = $emailConfig['password'];
        $this->mailer->SMTPSecure = $emailConfig['smtp_secure'];
        $this->mailer->Port = $emailConfig['port'];

        if (!$this->mailer->send()) {
            throw new \Exception('Message could not be sent ' . $this->mailer->ErrorInfo);
        } else {
            //your implementation here
        }
    }

    /**
     * @return array
     */
    public function getEmailConfiguration()
    {
        return $this->config->getConfigItem('email');
    }

    /**
     * Set the value of isHtml
     *
     * @return  self
     */
    public function setIsHtml($isHtml)
    {
        $this->isHtml = $isHtml;

        return $this;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the value of body
     *
     * @return  self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the value of alternativeBody
     *
     * @return  self
     */
    public function setAlternativeBody($alternativeBody)
    {
        $this->alternativeBody = $alternativeBody;

        return $this;
    }

    /**
     * @return void
     */
    private function validate()
    {
        if (!$this->isValid()) {
            throw new \Exception(
                'The email must have a body, an alternative body, and a subject to be valid'
            );
        }
    }

    /**
     * @return boolean
     */
    private function isValid()
    {
        return
            $this->body !== null &&
            $this->alternativeBody !== null &&
            $this->subject !== null;
    }
}
