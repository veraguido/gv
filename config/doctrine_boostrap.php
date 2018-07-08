<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

$config = new \Gvera\Helpers\config\Config();
$paths = array(__DIR__ . "/../src/Models");
$isDevMode = $config->getConfig('devmode');
$mysqlConfig = $config->getConfig('mysql');
// the connection configuration
$dbParams = array(
    'driver'   => $mysqlConfig['driver'],
    'host'     => $mysqlConfig['host'],
    'user'     => $mysqlConfig['username'],
    'password' => $mysqlConfig['password'],
    'dbname'   => $mysqlConfig['db_name'],
);


$classLoader = new ClassLoader('Doctrine\DBAL\Migrations', __DIR__ . '/../vendor/doctrine/migrations/lib');
$classLoader->register();

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
