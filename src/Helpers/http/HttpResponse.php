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
    const CONTENT_TYPE = "Content-Type";
    const CONTENT_TYPE_CSS = "text/css";
    const CONTENT_TYPE_JSON = "application/json";
    const CONTENT_TYPE_PDF = "application/pdf";
    const CONTENT_TYPE_PLAIN_TEXT = "text/plain";
    const CONTENT_TYPE_XML = "text/xml";
    const HTTP_RESPONSE_NOT_FOUND = "HTTP/1.0 404 Not Found";
    const HTTP_RESPONSE_BAD_REQUEST = "HTTP/1.0 404 Not Found";
    const HTTP_RESPONSE_UNAUTHORIZED = "HTTP/1.1 401 Unauthorized";
    const LOCATION = "Location";

    private $serverResponse;

    /**
     * @param string|array|TransformerAbstract $response
     * @return void
     * @throws NotImplementedMethodException
     */
    public function response($response, $statusCode = 200)
    {
        if (is_a($response, TransformerAbstract::class)) {
            $this->serverResponse->end(json_encode($response->transform()));
            return;
        }
        $this->serverResponse->status($statusCode);
        $this->serverResponse->end(is_array($response) ? json_encode($response) : (string) $response);
    }
    /**
     * @param $url
     */
    public function redirect($url)
    {
        $this->setHeader(self::LOCATION, $url);
    }

    public function notFound()
    {
        $this->setHeader('', self::HTTP_RESPONSE_NOT_FOUND);
    }

    public function badRequest()
    {
        $this->setHeader('', self::HTTP_RESPONSE_BAD_REQUEST);
    }

    public function unauthorized()
    {
        $this->setHeader('', self::HTTP_RESPONSE_UNAUTHORIZED);
    }

    /**
     * @param $header
     */
    public function setHeader($key, $value)
    {
        $this->serverResponse->header($key, $value);
    }

    public function responseExit()
    {
        exit;
    }

    /**
     * @param $message
     */
    public function terminate($message)
    {
        //die($message);
    }

    public function asJson()
    {
        $this->setContentType(self::CONTENT_TYPE_JSON);
    }

    public function asXML()
    {
        $this->setContentType(self::CONTENT_TYPE_XML);
    }

    public function asCSS()
    {
        $this->setContentType(self::CONTENT_TYPE_CSS);
    }

    public function asPlainText()
    {
        $this->setContentType(self::CONTENT_TYPE_PLAIN_TEXT);
    }

    public function asPDF()
    {
        $this->setContentType(self::CONTENT_TYPE_PDF);
    }

    private function setContentType($type)
    {
        $this->setHeader(self::CONTENT_TYPE, $type);
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    public function printError(int $errorCode, string $message)
    {
        $this->response(['code' => $errorCode, 'message' => $message]);
    }

    public function setServerResponse($serverResponse)
    {
        $this->serverResponse = $serverResponse;
    }
}
