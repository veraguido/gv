<?php

namespace Gvera\Helpers\http;

use Gvera\Exceptions\NotImplementedMethodException;
use Gvera\Helpers\transformers\TransformerAbstract;

class TransformerResponse extends JSONResponse
{

    /**
     * @var TransformerAbstract
     */
    private $transformer;

    /**
     * TransformerResponse constructor.
     * @param TransformerAbstract $transformer
     * @param string $httpCode
     * @throws NotImplementedMethodException
     */
    public function __construct(TransformerAbstract $transformer, $httpCode = Response::HTTP_RESPONSE_OK)
    {
        $this->transformer = $transformer;
        parent::__construct($transformer->transform(), $httpCode);
    }

    /**
     * @return string
     * @throws NotImplementedMethodException
     */
    public function getContent(): string
    {
        return parent::getContent();
    }
}
