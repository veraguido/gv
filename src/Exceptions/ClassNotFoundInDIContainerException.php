<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 02/05/18
 * Time: 23:07
 */

namespace Gvera\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class ClassNotFoundInDIContainerException extends GvException implements NotFoundExceptionInterface
{

}
