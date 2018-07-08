<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 08/07/18
 * Time: 11:01
 */

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
