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
    /**
     * @param Response $response
     */
    public function response(Response $response)
    {
        $this->setHeader($response->getContentType());
        $this->setHeader($response->getCode());
        $this->setHeader($response->getAuth());
        echo $response->getContent();
    }
    /**
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: " . $url);
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
}
