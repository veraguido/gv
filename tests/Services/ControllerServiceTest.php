<?php

use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Services\ControllerService;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\FileManager;
use Gvera\Helpers\http\HttpResponse;
use Gvera\Helpers\config\Config;
use Gvera\Controllers\GvController;
use Gvera\Exceptions\InvalidControllerException;
use Gvera\Helpers\annotations\AnnotationUtil;

class ControllerServiceTest extends \PHPUnit\Framework\TestCase
{
    private $controllerService;

    public function setUp()
    {
        $this->controllerService = new ControllerService();
        $this->controllerService->setServerRequest($this->getServerRequest());
        $this->controllerService->setServerResponse($this->getServerResponse());
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
     * @expectedException Exception
     */
    public function testEmptyUriPath()
    {
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
    public function testRedirectToDefault()
    {
        $this->controllerService->redirectToDefault($this->getDiContainer());
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function testServiceExceptions()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            "/gvcontroller"
        );
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function testVersionException()
    {
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
                $this->equalTo('annotationUtil')
            ))
            ->will(
                $this->returnCallback(array($this, 'httpCallBack'))
            );

        return $diContainer;
    }

    public function httpCallBack($param) {
        return $this->getMockedHttp($param);
    }

    private function getServerRequest() 
    {
        $serverRequest = new stdClass();
        $serverRequest->server = ['request_method' => 'GET'];
        return $serverRequest;
    }

    private function getServerResponse()
    {
        $serverResponse = new stdClass();
        return $serverResponse;
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
        if ($type === 'httpRequest') {
            $httpRequest = new HttpRequest(
                new FileManager($this->getMockedConfig())
            );
            $httpRequest->setServerRequest($this->getServerRequest());
            return $httpRequest;
        }

        if ($type === 'annotationUtil') {
            return $this->getMockedannotationUtil();
        }

        $httpResponse = $this->createMock(HttpResponse::class);
        $httpResponse->expects($this->any())
            ->method('asJson')
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
