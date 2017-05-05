<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 05/05/17
 * Time: 16:15
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(__DIR__ . "/../src/Models");
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'gvera',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);