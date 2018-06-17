<?php

use PHPUnit\Framework\TestCase;
use Gvera\Services\AnnotationService;
use Gvera\Controllers\Index;
use Gvera\Helpers\http\HttpRequest;

class AnnotationServiceTest extends TestCase
{
    /**
     * @test
     * @expectedException ReflectionException
     */
    public function getAnnotationContentFromMethodTest()
    {
        $service = new AnnotationService();
        $methods = $service->getAnnotationContentFromMethod(
            Index::class,
            'index',
            AnnotationService::HTTP_ANNOTATION
        );

        $this->assertTrue(count($methods) > 0);

        $secondTest = $service->getAnnotationContentFromMethod(
            Index::class,
            'cachetype',
            AnnotationService::HTTP_ANNOTATION
        );

        $this->assertTrue(count($secondTest) == 0);

        $thirdTest = $service->getAnnotationContentFromMethod(
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
        $service = new AnnotationService();

        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->expects($this->any())
            ->method('getRequestType')
            ->willReturn('GET');
        
        $this->assertTrue(
            $service->validateMethods($methods, $httpRequest)
        );

        $this->assertFalse(
            $service->validateMethods(["PUT"], $httpRequest)
        );

        $this->assertTrue(
            $service->validateMethods([], $httpRequest)
        );
    }
}