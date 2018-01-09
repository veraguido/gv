<?php

abstract class Task
{
    /**
     * Task constructor.
     * @throws Exception
     */
    public function __construct()
    {
        if (php_sapi_name() !== 'cli') {
            throw new Exception('Tasks must be only called from cli.');
        }
    }

    public function run()
    {
    }
}
