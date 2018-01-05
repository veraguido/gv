<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 05/01/18
 * Time: 13:35
 */

namespace Gvera\Helpers\entities;


use Doctrine\ORM\Tools\Setup;
use Gvera\Helpers\config\Config;

class EntityManager
{
    public static function getInstance()
    {
        $path = array('src/Models');

        $mysqlConfig = Config::getInstance()->getConfig('mysql');

        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($path, (bool) Config::getInstance()->getConfig('devmode'));
        return \Doctrine\ORM\EntityManager::create($dbParams, $doctrineConfig);
    }
}