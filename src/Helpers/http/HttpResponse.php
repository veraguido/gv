<?php namespace Gvera\Helpers\http;

/**
 * Class HttpResponse
 * @package Gvera\Helpers\http
 * This is a response wrapper for the response object.
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

    public function responseExit()
    {
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
