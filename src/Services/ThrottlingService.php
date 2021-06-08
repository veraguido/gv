<?php

namespace Gvera\Services;

use Exception;
use Gvera\Cache\Cache;
use Gvera\Exceptions\InvalidArgumentException;
use Gvera\Exceptions\ThrottledException;

class ThrottlingService
{
    const PREFIX_THROTTLING = 'gv_throttling_';
    /**
     * @var int
     * By default 4 requests per second.
     */
    private $allowedRequestsPerSecond = 4;
    /**
     * @var string
     */
    private $ip;

    public function __construct()
    {
    }

    /**
     * @throws ThrottledException
     * @throws InvalidArgumentException
     * @throws Exception
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
            if ($sec <= (1 / $this->allowedRequestsPerSecond)) {
                throw new ThrottledException('request not allowed', ['ip' => $this->ip]);
            }
        }
        
        $cache->save($key, microtime(true), 10);
    }
    
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }
    
    public function setAllowedRequestsPerSecond($rps)
    {
        $this->allowedRequestsPerSecond = $rps;
    }
}
