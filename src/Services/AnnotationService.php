<?php

namespace Gvera\Services;

use Gvera\Helpers\http\HttpRequest;
use Gvera\Exceptions\InvalidHttpMethodException;

class AnnotationService
{

    public const HTTP_ANNOTATION = '@httpMethod';

    /**
     * @param $className
     * @param $method
     * @param $annotationName
     * @throws \ReflectionException
     * @return array|boolean
     */
    public function getAnnotationContentFromMethod($className, $method, $annotationName)
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $comments = explode("\n", $reflectionMethod->getDocComment());
        return $this->getAnnotationFromArray($comments, $annotationName);
    }

    public function validateMethods(array $allowedMethods, HttpRequest $httpRequest)
    {
        if (count($allowedMethods) < 1) {
            return true;
        }

        $currentMethod = $httpRequest->getRequestType();
        return in_array($currentMethod, $allowedMethods);
    }

    private function getAnnotationFromArray($comments, $annotationName)
    {
        foreach ($comments as $comment) {
            $stringPosition = strpos($comment, $annotationName);
            if (false !== $stringPosition) {
                $leftString = substr($comment, $stringPosition + strlen($annotationName), strlen($comment));
                return $this->getArrayFromComment($leftString);
            }
        }

        return [];
    }

    private function getArrayFromComment($string)
    {
        $filteredString = str_replace(array('(', ')', '"', ','), '', $string);
        return explode(" ", $filteredString);
    }
}
