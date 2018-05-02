<?php
namespace Gvera\Helpers\entities;

use Doctrine\ORM\Tools\Setup;
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
class EntityManager
{
    private $emInstance;

    /**
     * EntityManager constructor.
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(Config $config)
    {
        $path = array('src/Models');

        $mysqlConfig = $config->getConfig('mysql');

        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'mysql',
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(
            $path,
            (bool) $config->getConfig('devmode')
        );

        $this->emInstance = \Doctrine\ORM\EntityManager::create($dbParams, $doctrineConfig);
    }

    public function getInstance()
    {
        return $this->emInstance;
    }
}
