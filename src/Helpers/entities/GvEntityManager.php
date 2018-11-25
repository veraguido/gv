<?php
namespace Gvera\Helpers\entities;

use Doctrine\Common\Cache\RedisCache;
use Doctrine\ORM\Tools\Setup;
use Gvera\Helpers\config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\EventManager;

/**
 * Entities Class Doc Comment
 *
 * @category Class
 * @package  src/helpser/entities
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class GvEntityManager extends EntityManager
{
    /**
     * GvEntityManager constructor.
     * @param Config $config
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __construct(Config $config)
    {
        $path = array('src/Models');
        $mysqlConfig = $config->getConfig('mysql');
        $redisConfig = $config->getConfig('redis');
        $dbParams = array(
            'driver'   => $mysqlConfig['driver'],
            'host'     => $mysqlConfig['host'],
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );

        $cache = new RedisCache();
        $redis = new \Redis();
        $redis->connect($redisConfig['host'], $redisConfig['port']);
        $cache->setRedis($redis);

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(
            $path,
            (bool) $config->getConfig('devmode'),
            null,
            $cache
        );
        
        $connection = DriverManager::getConnection($dbParams, $doctrineConfig);
        parent::__construct($connection, $doctrineConfig, new EventManager());
    }
}
