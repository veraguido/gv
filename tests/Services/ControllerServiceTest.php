<?php

use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Services\ControllerService;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\FileManager;
use Gvera\Helpers\http\HttpResponse;
use Gvera\Helpers\config\Config;
use Gvera\Controllers\GvController;
use Gvera\Exceptions\InvalidControllerException;

class ControllerServiceTest extends \PHPUnit\Framework\TestCase
{
    private $controllerService;

    public function setUp()
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
            ["path" => "/"]
        );

        $this->assertTrue(
            $this->controllerService->getControllerName() === "Index"
        );

        $this->assertTrue(
            $this->controllerService->getMethodName() === "index"
        );

        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            ["path" => "/examples/asd"]
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
        $controllerName = GvController::DEFAULT_CONTROLLER;
        $methodName = GvController::DEFAULT_METHOD; 
        $this->assertTrue(
             $this->controllerService->getControllerName() === $controllerName
        );
        $this->assertTrue(
            $this->controllerService->getMethodName() === $methodName
        );
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function testServiceExceptions()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            ["path" => "/gvcontroller"]
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
            ["path" => "/v5/index/asd"]
        );
    }

    /**
     * @test
     */
    public function testSubControllers()
    {
        $this->controllerService->startControllerLifecyle(
            $this->getDiContainer(),
            ["path" => "/v0/moreexamples/other"]
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
                $this->equalTo('httpResponse')
            ))
            ->will(
                $this->returnCallback(array($this, 'httpCallBack'))
            );

        return $diContainer;
    }

    public function httpCallBack($param) {
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

    private function getMockedHttp($type) 
    {
        if ($type === 'httpRequest') {
            return null;
        }

        $httpResponse = $this->createMock(HttpResponse::class);
        $httpResponse->expects($this->any())
            ->method('asJson')
            ->willReturn(true);

        return $httpResponse;
    }
}
