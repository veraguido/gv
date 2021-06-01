<?php

use Gvera\Helpers\annotations\AnnotationUtil;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\http\FileManager;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\HttpResponse;
use Gvera\Services\ControllerService;
use PHPUnit\Framework\TestCase;

class ControllerServiceTest extends TestCase
{
    private $controllerService;

    public function setUp():void
    {
        $this->controllerService = new ControllerService();
        $this->controllerService->setControllerAutoloadingNames($this->getAutoloadingNames());
        $this->controllerService->setDiContainer($this->getDiContainer());
    }

    /**
     * @test
     */
    public function testControllerLifeCycle()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/"
        );

        $this->assertTrue(
            $this->controllerService->getControllerName() === "Index"
        );

        $this->assertTrue(
            $this->controllerService->getMethodName() === "index"
        );
    }

    /**
     * @test
     */
    public function testEmptyUriPath()
    {
        $this->expectException(Exception::class);
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            null
        );
    }

    /**
     * @test
     */
    public function testStartControllerLifeCycle()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/examples/asd"
        );

        $this->assertTrue(
            $this->controllerService->getControllerName() === "Examples"
        );
        $this->assertTrue(
            $this->controllerService->getMethodName() === 'asd'
        );
    }

    /**
     * @test
     */
    public function testServiceExceptions()
    {
        $this->expectException(Exception::class);
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/gvcontroller"
        );
    }

    /**
     * @test
     */
    public function testVersionException()
    {
        $this->expectException(\Gvera\Exceptions\NotFoundException::class);
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/v5/index/asd"
        );
    }

    /**
     * @test
     */
    public function testSubControllers()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/v0/moreexamples/other"
        );

        $this->assertTrue(
            $this->controllerService->getControllerName() === 'MoreExamples'
        );
        $this->assertTrue(
            $this->controllerService->getMethodName() === 'other'
        );
    }

    /**
     * @test
     */
    public function testSpecificControllerLifeCycle()
    {
        $this->controllerService->generateSpecificControllerLifeCycle(
            'index',
            'index'
        );

        $this->assertTrue(
            $this->controllerService->getControllerName() === "Index"
        );

        $this->assertTrue(
            $this->controllerService->getMethodName() === "index"
        );
    }

    private function getDiContainer()
    {
        $diContainer = $this->createMock(DIContainer::class);
        $diContainer->expects($this->any())
            ->method("get")
            ->with($this->logicalOr(
                $this->equalTo('httpRequest'),
                $this->equalTo('httpResponse'),
                $this->equalTo('annotationUtil'),
                $this->equalTo('twigService')
            ))
            ->will(
                $this->returnCallback(array($this, 'httpCallBack'))
            );

        return $diContainer;
    }

    public function httpCallBack($param)
    {
        return $this->getMockedHttp($param);
    }

    private function getAutoloadingNames()
    {
        return  [
            'index' => 'Index',
            'examples' => 'Examples',
            'gvcontroller' => 'GvController',
            'v0' => ['moreexamples' => 'MoreExamples']
        ];
    }

    private function getMockedHttp($type = '')
    {
        $validator = new \Gvera\Helpers\http\HttpRequestValidator(new \Gvera\Helpers\validation\ValidationService());
        if ($type === 'httpRequest') {
            $_SERVER['REQUEST_METHOD'] = 'GET';
            return new HttpRequest(
                new FileManager($this->getMockedConfig()),
                $validator
            );
        }

        if ($type === 'annotationUtil') {
            return $this->getMockedannotationUtil();
        }

        if ($type === 'twigService') {
            return $this->getMockedTwigService();
        }

        $httpResponse = $this->createMock(HttpResponse::class);
        $httpResponse->expects($this->any())
            ->method('response')
            ->willReturn(true);

        $httpResponse->expects($this->any())
            ->method('redirect');

        return $httpResponse;
    }

    private function getMockedConfig()
    {
        $config = $this->createMock(Config::class);

        return $config;
    }

    private function getMockedTwigService()
    {
        $config = $this->getMockedConfig();
        return new \Gvera\Services\TwigService($config);
    }

    private function getMockedannotationUtil()
    {
        $annotationUtilMock = $this->createMock(AnnotationUtil::class);
        $annotationUtilMock->expects($this->any())
            ->method('validateMethods')
            ->willReturn(true);
        $annotationUtilMock->expects($this->any())
            ->method('getAnnotationContentFromMethod')
            ->willReturn([]);
        return $annotationUtilMock;
    }
}
