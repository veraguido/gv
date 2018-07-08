<?php

namespace Gvera\Helpers\transformers;

use Gvera\Exceptions\NotImplementedMethodException;

class TransformerAbstract
{
    protected $object;
    protected $context;

    public function __construct($object, $context = null)
    {
        $this->object = $object;
        $this->context = $context;
    }

    /**
     * @param $object
     * @param null $context
     * @return array
     * @throws NotImplementedMethodException
     */
    public function transform():array
    {
        throw new NotImplementedMethodException(
            "the transformer object should implement the transform method",
            ['class' => self::class, 'object' => get_class($this->object), 'context' => $this->context]
        );
    }
}
