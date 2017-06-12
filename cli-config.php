<?php

require_once 'config/doctrine_boostrap.php';

//return new \Symfony\Component\Console\Helper\HelperSet(array('em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)));
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);