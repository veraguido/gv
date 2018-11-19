<?php

namespace Gvera\Cache;

use Predis\Client;

class PoolableClientCache
{
    private $bufferSize;
    private $index = 0;
    private $pool = [];

    /**
     * PoolableClientCache constructor.
     * @param int $bufferSize
     * @param string $clientClassName
     * @param array $config
     * @throws \ReflectionException
     */
    public function __construct(int $bufferSize, string $clientClassName, array $config)
    {
        $this->constructPool($bufferSize, $clientClassName, $config);
    }

    /**
     * @return Client
     */
    public function nextClient(): Client
    {
        if (($this->index + 1) < ($this->bufferSize - 1)) {
            $this->index++;
            return $this->returnCurrentClient();
        }

        $this->index = 0;
        return $this->returnCurrentClient();
    }

    private function returnCurrentClient()
    {
        return $this->pool[$this->index];
    }


    /**
     * @param $bufferSize
     * @param $clientClass
     * @param $config
     * @throws \ReflectionException
     */
    public function constructPool($bufferSize, $clientClass, $config)
    {
        for ($i = 0; $i < $bufferSize; $i++) {
            $clientInstance = new Client($config);
            array_push($this->pool, $clientInstance);
        }
    }

    /**
     * @return void
     */
    public function destructPool()
    {
        $this->pool = [];
        $this->index = 0;
        $this->bufferSize = 0;
    }
}
