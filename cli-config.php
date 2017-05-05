<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 05/05/17
 * Time: 16:25
 */

require_once 'config/doctrine_boostrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);