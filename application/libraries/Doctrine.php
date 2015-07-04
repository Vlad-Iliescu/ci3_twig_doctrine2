<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Class Doctrine
 * @property EntityManager $em
 */
class Doctrine
{
    public $em;
//    const ACTIVE_DB = 'db';

    public function __construct($isDevMode = false)
    {
        // Load the database configuration from CodeIgniter
        require_once(APPPATH . 'config/database.php');

        /**
         * @var array $db
         * @var string $active_group
         */
        define('ACTIVE_DB', $active_group);

        $connection_options = array(
            'driver'        => 'pdo_mysql',
            'user'          => $db[ACTIVE_DB]['username'],
            'password'      => $db[ACTIVE_DB]['password'],
            'host'          => $db[ACTIVE_DB]['hostname'],
            'dbname'        => $db[ACTIVE_DB]['database'],
            'charset'       => $db[ACTIVE_DB]['char_set'],
            'driverOptions' => array(
                'charset'   => $db[ACTIVE_DB]['char_set'],
            ),
        );

        $models_path = APPPATH . 'models';
        $config = Setup::createAnnotationMetadataConfiguration(array($models_path), $isDevMode);

        $this->em = EntityManager::create($connection_options, $config);

        // With this configuration, your model files need to be in application/models/Entity
        // e.g. Creating a new Entity\User loads the class from application/models/Entity/User.php
//        $models_namespace = 'Entity';
//        $models_path = APPPATH . 'models';
//        $proxies_dir = APPPATH . 'models/Proxies';
//        $metadata_paths = array(APPPATH . 'models');
//
//        // Set $dev_mode to TRUE to disable caching while you develop
//        $config = Setup::createAnnotationMetadataConfiguration($metadata_paths, $dev_mode = true, $proxies_dir, null, false);
//        $this->em = EntityManager::create($connection_options, $config);
//
        $this->debugStack = new Doctrine\DBAL\Logging\DebugStack();
        $this->em->getConnection()->getConfiguration()->setSQLLogger($this->debugStack);
//
        spl_autoload_extensions('.php');
        spl_autoload_register(array($this, 'autoLoad'));
//
//        $loader = new ClassLoader($models_namespace, $models_path);
//        $loader->register();
    }

    /**
     * @param string $class
     */
    public function autoLoad($class) {
        if ((strpos($class, 'CI_') === 0) || (strpos($class, 'Base_') === 0)) {
            return;
        }
        if (file_exists(APPPATH. 'models/' . $class . '.php')) {
            include_once(APPPATH. 'models/' . $class . '.php');
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }
}
