<?php
define('APPPATH', 'application/');
define('BASEPATH', 'system');

require_once  'application/libraries/Doctrine.php';

$doctrine  = new \Doctrine();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($doctrine->getEntityManager());