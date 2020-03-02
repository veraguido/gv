<?php


namespace Gvera\Helpers\http;


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
     */
    public function __construct(TransformerAbstract $transformer, $code)
    {
        $this->transformer = $transformer;
        parent::__construct($code);
    }

    /**
     * @return string
     * @throws \Gvera\Exceptions\NotImplementedMethodException
     */
    public function getContent(): string
    {
        return json_encode($this->transformer->transform(parent::getContent()));
    }
}