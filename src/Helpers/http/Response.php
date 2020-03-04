<?php


namespace Gvera\Helpers\http;

class Response
{
    const CONTENT_TYPE_HTML = "Content-Type: text/html";
    const CONTENT_TYPE_CSS = "Content-Type: text/css";
    const CONTENT_TYPE_JSON = "Content-Type: application/json";
    const CONTENT_TYPE_PDF = "Content-Type: application/pdf";
    const CONTENT_TYPE_PLAIN_TEXT = "Content-Type: text/plain";
    const CONTENT_TYPE_XML = "Content-Type: text/xml";


    const HTTP_RESPONSE_NOT_FOUND = "HTTP/1.1 404 Not Found";
    const HTTP_RESPONSE_BAD_REQUEST = "HTTP/1.1 400 Bad Request";
    const HTTP_RESPONSE_OK = "HTTP/1.1 200 OK";
    const HTTP_RESPONSE_TOO_MANY_REQUESTS = "HTTP/1.1 429 Too Many Requests";
    const HTTP_RESPONSE_UNAUTHORIZED = "HTTP/1.1 401 Unauthorized";


    const NO_CACHE = 'Cache-Control: no-cache, must-revalidate, max-age=0';
    const BASIC_AUTH_ACCESS_DENIED = 'WWW-Authenticate: Basic realm="Access denied"';

    /**
     * @var string $contentType
     */
    private $contentType;
    /**
     * @var string $code
     */
    private $code;
    /**
     * @var string $content
     */
    private $content;
    /**
     * @var string
     */
    private $auth;

    /**
     * Response constructor.
     * @param string $content
     * @param string $contentType
     * @param string $code
     * @param string $auth
     */
    public function __construct(
        string $content = '',
        string $contentType = self::CONTENT_TYPE_HTML,
        string $code = self::HTTP_RESPONSE_OK,
        string $auth = ''
    ) {
        $this->contentType = $contentType;
        $this->code = $code;
        $this->content = $content;
        $this->auth = $auth;
    }

    /**
     * @return string
     */
    public function getAuth(): string
    {
        return $this->auth;
    }


    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
