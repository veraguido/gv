<?php

namespace Services;

use Gvera\Cache\Cache;
use Gvera\Exceptions\ThrottledException;
use Gvera\Services\ThrottlingService;
use PHPUnit\Framework\TestCase;

class ThrottlingServiceTest extends TestCase
{
    private $service;
    private $ip = "123";
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ThrottlingService();
        $this->service->setAllowedRequestsPerSecond(1);
        $this->service->setIp($this->ip);
    }

    /**
     * @test
     */
    public function testInvalidRate()
    {
        $this->expectException(ThrottledException::class);
        $this->service->validateRate();
        $this->service->validateRate();
    }

    /**
     * @test
     */
    public function testValidRate()
    {
        sleep(1);
        $this->service->validateRate();
        $this->assertTrue(Cache::getCache()->exists(ThrottlingService::PREFIX_THROTTLING.$this->ip));
    }

}