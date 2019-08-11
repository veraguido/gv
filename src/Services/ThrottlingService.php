<?php

namespace Gvera\Services;

use Gvera\Cache\Cache;
use Gvera\Helpers\http\HttpResponse;
use src\Exceptions\ThrottledException;

class ThrottlingService
{
    const PREFIX_THROTTLING = 'gv_throttling_';
    const ALLOWED_REQUESTS_PER_SECOND = 4;
    /**
     * @var HttpResponse
     */
    private $httpResponse;
    /**
     * @var string
     */
    private $ip;

    public function __construct()
    {
    }

    /**
     * @throws ThrottledException
     * @throws \Gvera\Exceptions\InvalidArgumentException
     */
    public function validateRate()
    {
        if (!isset($this->ip)) {
            throw new \InvalidArgumentException('Unable to validate throttling without ip');
        }
        
        $cache = Cache::getCache();
        $key = self::PREFIX_THROTTLING . $this->ip;
        if ($cache->exists($key)) {
            $last = $cache->load($key);
            $current = microtime(true);
            $sec =  abs($last - $current);
            if ($sec <= (1 / self::ALLOWED_REQUESTS_PER_SECOND)) {
                throw new ThrottledException('request not allowed', ['ip' => $this->ip]);
                return;
            }
        }
        
        $cache->save($key, microtime(true), 10);
    }
    
    public function getIp() :string
    {
        return $this->ip;
    }
    
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }
}
