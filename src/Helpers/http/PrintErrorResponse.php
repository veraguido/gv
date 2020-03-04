<?php


namespace Gvera\Helpers\http;

class PrintErrorResponse extends JSONResponse
{
    /**
     * @var string
     */
    private $errorCode;
    /**
     * @var string
     */
    private $errorMessage;
    /**
     * @var string
     */
    private $httpCode;

    /**
     * PrintErrorResponse constructor.
     * @param string $errorCode
     * @param string $errorMessage
     * @param string $httpCode
     */
    public function __construct(string $errorCode, string $errorMessage, string $httpCode)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->httpCode = $httpCode;
        parent::__construct(['code' => $errorCode, 'message' => $errorMessage], $httpCode);
    }
}
