<?php namespace Gvera\Helpers\http;

use Gvera\Exceptions\NotImplementedMethodException;
use Gvera\Helpers\transformers\TransformerAbstract;

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
    const HTTP_RESPONSE_NOT_FOUND = "HTTP/1.1 404 Not Found";
    const HTTP_RESPONSE_BAD_REQUEST = "HTTP/1.1 400 Bad Request";
    const HTTP_RESPONSE_TOO_MANY_REQUESTS = "HTTP/1.1 429 Too Many Requests";
    const HTTP_RESPONSE_UNAUTHORIZED = "HTTP/1.1 401 Unauthorized";
    const NO_CACHE = 'Cache-Control: no-cache, must-revalidate, max-age=0';
    const BASIC_AUTH_ACCESS_DENIED = 'WWW-Authenticate: Basic realm="Access denied"';

    /**
     * @param string|array|TransformerAbstract $response
     * @return void
     * @throws NotImplementedMethodException
     */
    public function response($response)
    {
        if (is_a($response, TransformerAbstract::class)) {
            echo json_encode($response->transform());
            return;
        }

        echo is_array($response) ? json_encode($response) : (string) $response;
    }
    /**
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: " . $url);
    }

    public function notFound()
    {
        $this->setHeader(self::HTTP_RESPONSE_NOT_FOUND);
        return $this;
    }

    public function badRequest()
    {
        $this->setHeader(self::HTTP_RESPONSE_BAD_REQUEST);
        return $this;
    }

    public function tooManyRequests()
    {
        $this->setHeader(self::HTTP_RESPONSE_TOO_MANY_REQUESTS);
        return $this;
    }

    public function unauthorized()
    {
        $this->setHeader(self::HTTP_RESPONSE_UNAUTHORIZED);
        return $this;
    }

    /**
     * @param $header
     */
    public function setHeader($header)
    {
        header($header);
    }

    public function responseExit()
    {
        exit;
    }

    /**
     * @param $message
     */
    public function terminate($message = '')
    {
        die($message);
    }

    public function asJson()
    {
        $this->setHeader(self::CONTENT_TYPE_JSON);
        return $this;
    }

    public function asXML()
    {
        $this->setHeader(self::CONTENT_TYPE_XML);
        return $this;
    }

    public function asCSS()
    {
        $this->setHeader(self::CONTENT_TYPE_CSS);
        return $this;
    }

    public function asPlainText()
    {
        $this->setHeader(self::CONTENT_TYPE_PLAIN_TEXT);
        return $this;
    }

    public function asPDF()
    {
        $this->setHeader(self::CONTENT_TYPE_PDF);
        return $this;
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    public function printError(int $errorCode, string $message)
    {
        $this->response(['code' => $errorCode, 'message' => $message]);
    }
}
