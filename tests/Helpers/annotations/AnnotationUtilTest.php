<?php

use PHPUnit\Framework\TestCase;
use Gvera\Controllers\Index;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\annotations\AnnotationUtil;

class AnnotationUtilTest extends TestCase
{
    private $util;

    /**
     * @httpMethod("GET")
     */
    public function setUp():void
    {
        $this->util = new AnnotationUtil();
    }

    public function tearDown():void
    {
    }
    /**
     * @test
     */
    public function getAnnotationContentFromMethodHappyPathTest()
    {
        $methods = $this->util->getAnnotationContentFromMethod(
            AnnotationUtilTest::class,
            'setup',
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
            AnnotationUtilTest::class,
            'tearDown',
            AnnotationUtil::HTTP_ANNOTATION
        );

        $this->assertTrue(count($secondTest) == 0);
    }

    /**
     * @test
     */
    public function getAnnotationContentFromMethodExceptionTest()
    {
        $this->expectException(ReflectionException::class);
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
