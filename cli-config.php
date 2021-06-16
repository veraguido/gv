<?php

require_once __DIR__ . '/config/doctrine_boostrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
