<?php
// define needed constants
define('APPPATH', 'application' . DIRECTORY_SEPARATOR);
define('BASEPATH', 'system');
/** @var array $config */
require_once APPPATH . 'config/doctrine.php';
/**
 * @var string $active_group
 * @var array $db
 */
require_once APPPATH . 'config/database.php';
/**
 * @class Doctrine
 */
require_once APPPATH . 'libraries/Doctrine.php';
// determine active group
if (isset($config['active_group'])) {
    $active_group = $config['active_group'];
}
// load active group configuration
$config['database'] = Doctrine::mapConfigToDoctrine(Doctrine::filterConfigArray($db[$active_group]));
// Load metadata driver
$metadataConfiguration = Doctrine::createMetadataConfiguration($config);
// create entity manager
$entityManager = \Doctrine\ORM\EntityManager::create(
    $config['database'],
    $metadataConfiguration,
    $config['event_manager']
);
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
