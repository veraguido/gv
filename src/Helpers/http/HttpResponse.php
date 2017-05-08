<?php namespace Gvera\Helpers\http;

/**
 * Created by PhpStorm.
 * User: guido
 * Date: 08/05/17
 * Time: 13:34
 */
class HttpResponse
{

    const CONTENT_TYPE_CSS = "Content-Type: text/css";
    const CONTENT_TYPE_JSON = "Content-Type: application/json";
    const CONTENT_TYPE_PDF = "Content-Type: application/pdf";
    const CONTENT_TYPE_PLAIN_TEXT = "Content-Type: text/plain";
    const CONTENT_TYPE_XML = "Content-Type: text/xml";

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new HttpResponse();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }

    public function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        exit;
    }

    public function customHeader($header)
    {
        header($header);
        exit;
    }

    public function setHeader($header)
    {
        header($header);
    }

    public function responseExit() {
        exit;
    }

    public function asJson()
    {
        $this->setHeader(self::CONTENT_TYPE_JSON);
    }

    public function asXML()
    {
        $this->setHeader(self::CONTENT_TYPE_XML);
    }

    public function asCSS()
    {
        $this->setHeader(self::CONTENT_TYPE_CSS);
    }

    public function asPlainText()
    {
        $this->setHeader(self::CONTENT_TYPE_PLAIN_TEXT);
    }

    public function asPDF()
    {
        $this->setHeader(self::CONTENT_TYPE_PDF);
    }
}