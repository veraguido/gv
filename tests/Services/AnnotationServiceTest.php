<?php

use PHPUnit\Framework\TestCase;
use Gvera\Services\AnnotationService;
use Gvera\Controllers\Index;
use Gvera\Helpers\http\HttpRequest;

class AnnotationServiceTest extends TestCase
{
    private $service;

    public function setUp()
    {
        $this->service = new AnnotationService();
    }
    /**
     * @test
     */
    public function getAnnotationContentFromMethodHappyPathTest()
    {
        $methods = $this->service->getAnnotationContentFromMethod(
            Index::class,
            'index',
            AnnotationService::HTTP_ANNOTATION
        );

        $this->assertTrue(count($methods) > 0);
    }

    /**
     * @test
     */
    public function getAnnotationContentFromMethodEmptyPathTest()
    {
        $secondTest = $this->service->getAnnotationContentFromMethod(
            Index::class,
            'cachetype',
            AnnotationService::HTTP_ANNOTATION
        );

        $this->assertTrue(count($secondTest) == 0);
    }

    /**
     * @test
     * @expectedException ReflectionException
     */
    public function getAnnotationContentFromMethodExceptionTest()
    {
        $this->service->getAnnotationContentFromMethod(
            Index::class,
            'test',
            AnnotationService::HTTP_ANNOTATION
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
            $this->service->validateMethods($methods, $httpRequest)
        );

        $this->assertFalse(
            $this->service->validateMethods(["PUT"], $httpRequest)
        );

        $this->assertTrue(
            $this->service->validateMethods([], $httpRequest)
        );
    }
}