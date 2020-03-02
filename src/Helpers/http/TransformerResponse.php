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
     * @param $code
     * @throws NotImplementedMethodException
     */
    public function __construct(TransformerAbstract $transformer, $code = Response::HTTP_RESPONSE_OK)
    {
        $this->transformer = $transformer;
        parent::__construct(json_encode($this->transformer->transform()), $code);
    }

    /**
     * @return string
     * @throws NotImplementedMethodException
     */
    public function getContent(): string
    {
        return $this->getContent();
    }
}