<?php

namespace Gvera\Helpers\transformers;

class UserTransformer extends TransformerAbstract
{
    public function transform(): array
    {
        return [
          'id' => $this->object->getId(),
            'email' => $this->object->getEmail()
        ];
    }
}
