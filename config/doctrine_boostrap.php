<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

$config = new \Gvera\Helpers\config\Config(__DIR__ . '/../config/config.yml');

$classLoader = new \Doctrine\Common\ClassLoader(
    'Doctrine\DBAL\Migrations',
    __DIR__ . '/../vendor/doctrine/migrations/lib'
);
$classLoader->register();

$entityManager = new \Gvera\Helpers\entities\GvEntityManager($config);
