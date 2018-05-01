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
 * @Inject config
 *
 */
class EntityManager
{
    public function getNewInstance()
    {
        $path = array('src/Models');

        $mysqlConfig = $this->config->getConfig('mysql');

        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'mysql',
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(
            $path,
            (bool) $this->config->getConfig('devmode')
        );

        return \Doctrine\ORM\EntityManager::create($dbParams, $doctrineConfig);
    }
}
