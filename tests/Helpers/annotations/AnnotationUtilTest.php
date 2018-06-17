<?php

use PHPUnit\Framework\TestCase;
use Gvera\Controllers\Index;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\annotations\AnnotationUtil;

class AnnotationUtilTest extends TestCase
{
    private $util;

    public function setUp()
    {
        $this->util = new AnnotationUtil();
    }
    /**
     * @test
     */
    public function getAnnotationContentFromMethodHappyPathTest()
    {
        $methods = $this->util->getAnnotationContentFromMethod(
            Index::class,
            'index',
            AnnotationUtil::HTTP_ANNOTATION
        );

        $this->assertTrue(count($methods) > 0);
    }

    /**
     * @test
     */
    public function getAnnotationContentFromMethodEmptyPathTest()
    {
        $secondTest = $this->util->getAnnotationContentFromMethod(
            Index::class,
            'cachetype',
            AnnotationUtil::HTTP_ANNOTATION
        );

        $this->assertTrue(count($secondTest) == 0);
    }

    /**
     * @test
     * @expectedException ReflectionException
     */
    public function getAnnotationContentFromMethodExceptionTest()
    {
        $this->util->getAnnotationContentFromMethod(
            Index::class,
            'test',
            AnnotationUtil::HTTP_ANNOTATION
        );
    }

    /**
     * @test
     */
    public function validateMethodsTest()
    {
        $methods = ["GET", "POST"];

        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->expects($this->any())
            ->method('getRequestType')
            ->willReturn('GET');
        
        $this->assertTrue(
            $this->util->validateMethods($methods, $httpRequest)
        );

        $this->assertFalse(
            $this->util->validateMethods(["PUT"], $httpRequest)
        );

        $this->assertTrue(
            $this->util->validateMethods([], $httpRequest)
        );
    }
}