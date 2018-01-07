<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 05/05/17
 * Time: 16:15
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

$paths = array(__DIR__ . "/../src/Models");
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'gv',
);


$classLoader = new ClassLoader('Doctrine\DBAL\Migrations', __DIR__ . '/../vendor/doctrine/migrations/lib');
$classLoader->register();

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);