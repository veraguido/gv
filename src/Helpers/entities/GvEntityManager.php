<?php
namespace Gvera\Helpers\entities;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Gvera\Helpers\config\Config;

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
    const PROXIES_PATH = __DIR__ . '/../../../var/proxies/';
    const MODELS_PATH = __DIR__ . '/../../../src/Models/';
    /**
     * GvEntityManager constructor.
     * @param Config $config
     * @throws DBALException
     */
    public function __construct(Config $config)
    {

        $devMode = $config->getConfigItem('devmode');
        $mysqlConfig = $config->getConfigItem('mysql');
        $cache = new ApcCache();
        if ($devMode) {
            $cache = new ArrayCache();
        }

        $doctrineConfig = new Configuration();
        $doctrineConfig->setMetadataCacheImpl($cache);
        $driverImpl = $doctrineConfig->newDefaultAnnotationDriver(self::MODELS_PATH);
        $doctrineConfig->setMetadataDriverImpl($driverImpl);
        $doctrineConfig->setQueryCacheImpl($cache);
        $doctrineConfig->setProxyDir(self::PROXIES_PATH);
        $doctrineConfig->setProxyNamespace('Gvera\Models');

        $doctrineConfig->setAutoGenerateProxyClasses($devMode);
        $dbParams = array(
            'driver'   => $mysqlConfig['driver'],
            'host'     => $mysqlConfig['host'],
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );


        $connection = DriverManager::getConnection($dbParams, $doctrineConfig);
        parent::__construct($connection, $doctrineConfig, new EventManager());
    }
}
