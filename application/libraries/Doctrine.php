<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Class Doctrine
 * @property EntityManager $em
 */
class Doctrine {
    /** @var EntityManager $em */
    public $em;
    /** @var  CI_Controller $CI */
    protected $CI;
    /** @var array $config */
    protected $config = array();
    /** @var  string $activeGroup */
    protected $activeGroup;
    /** @var \Doctrine\ORM\Configuration $metadataConfiguration */
    protected $metadataConfiguration;
    /** @var \Doctrine\DBAL\Logging\DebugStack $debugStack */
    protected $debugStack = null;

    public function __construct() {
        $this->CI =& get_instance();
        $this->loadConfig();
        $this->metadataConfiguration = self::createMetadataConfiguration($this->config);
        $this->createEntityManager();

        if ($this->config['dev_mode']) {
            $this->debugStack = new Doctrine\DBAL\Logging\DebugStack();
            $this->em->getConnection()->getConfiguration()->setSQLLogger($this->debugStack);
        }

        /** Add autoload for entities */
        spl_autoload_extensions('.php');
        spl_autoload_register(array($this, 'autoLoad'));
    }

    /**
     * @param string $class
     */
    public function autoLoad($class) {
        // Skip CI core and extensions that are already auto-loaded
        if ((strpos($class, 'CI_') === 0) ||
            (strpos($class, $this->CI->config->item('subclass_prefix')) === 0)
        ) {
            return;
        }
        if (file_exists(APPPATH . 'models/' . $class . '.php')) {
            include_once(APPPATH . 'models/' . $class . '.php');
        }
    }

    /**
     * @return \Doctrine\ORM\Configuration
     */
    public function getMetadataConfiguration() {
        return $this->metadataConfiguration;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    /**
     * @return string
     */
    public function getActiveGroup() {
        return $this->activeGroup;
    }

    /**
     * @return \Doctrine\DBAL\Logging\DebugStack
     */
    public function getDebugStack() {
        return $this->debugStack;
    }

    //============================
    //      STATIC METHODS
    //============================

    /**
     * @param string $key
     * @param array $config
     * @return bool
     */
    public static function checkKey($key, array $config) {
        return isset($config[$key]) || array_key_exists($key, $config);
    }

    /**
     * @param array $config
     * @return array
     */
    public static function mapConfigToDoctrine(array $config) {
        if (self::checkKey('dbdriver', $config)) {
            $config['driver'] = $config['dbdriver'];
            unset($config['dbdriver']);
        }
        if (self::checkKey('username', $config)) {
            $config['user'] = $config['username'];
            unset($config['username']);
        }
        if (self::checkKey('hostname', $config)) {
            $config['host'] = $config['hostname'];
            unset($config['hostname']);
        }
        if (self::checkKey('database', $config)) {
            $config['dbname'] = $config['database'];
            unset($config['database']);
        }
        if (self::checkKey('char_set', $config)) {
            $config['charset'] = $config['char_set'];
            if ($config['driver'] == 'mysqli') {
                $config['driverOptions']['charset'] = $config['char_set'];
            }
            unset($config['char_set']);
        }

        return $config;
    }

    /**
     * @param string $dbDriver
     * @return array
     * @throws Exception
     */
    public static function getAllowedConfigurations($dbDriver) {
        if ($dbDriver == 'pdo_sqlite') {
            return array('dbdriver', 'username', 'password', 'path', 'memory');
        } elseif ($dbDriver == 'pdo_mysql') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'unix_socket', 'char_set');
        } elseif ($dbDriver == 'drizzle_pdo_mysql') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'unix_socket');
        } elseif ($dbDriver == 'mysqli') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'unix_socket', 'char_set', 'driverOptions');
        } elseif ($dbDriver == 'pdo_pgsql') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'char_set', 'sslmode');
        } elseif ($dbDriver == 'pdo_oci' || $dbDriver == 'oci8') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'servicename', 'service', 'pooled', 'char_set',
                'instancename');
        } elseif ($dbDriver == 'pdo_sqlsrv' || $dbDriver == 'sqlsrv') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database');
        } elseif ($dbDriver == 'sqlanywhere') {
            return array('dbdriver', 'username', 'password', 'hostname', 'port',
                'database', 'persistent');
        }

        throw new \Exception('Unknown driver: "' . $dbDriver . '"!"');
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function filterConfigArray(array $config) {
        return array_intersect_key($config,
            array_flip(self::getAllowedConfigurations($config['dbdriver'])));
    }

    /**
     * @return \Doctrine\ORM\Configuration
     * @throws Exception
     */
    public static function createMetadataConfiguration(array $config) {
        if ($config['metadata_type'] == 'annotation') {
            return Setup::createAnnotationMetadataConfiguration(
                $config['metadata_path'],
                $config['dev_mode'],
                $config['proxy_dir'],
                $config['cache']
            );
        } elseif ($config['metadata_type'] == 'yml') {
            return Setup::createYAMLMetadataConfiguration(
                $config['metadata_path'],
                $config['dev_mode'],
                $config['proxy_dir'],
                $config['cache']
            );
        } elseif ($config['metadata_type'] == 'xml') {
            return Setup::createXMLMetadataConfiguration(
                $config['metadata_path'],
                $config['dev_mode'],
                $config['proxy_dir'],
                $config['cache']
            );
        }

        throw new Exception('Unknown metadata driver type "' . $config['metadata_type'] . '"!');
    }

    //============================
    //      PRIVATE METHODS
    //============================

    /**
     * Loads configuration from CI
     */
    private function loadConfig() {
        $this->CI->config->load('doctrine', TRUE, TRUE);
        $this->config = $this->CI->config->item('doctrine');

        // Load the database configuration from CodeIgniter
        /**
         * @var string $active_group
         * @var bool $query_builder
         * @var array $db
         */
        require_once(APPPATH . 'config/database.php');

        if (!isset($this->config['active_group'])) {
            $this->activeGroup = $active_group;
        } else {
            $this->activeGroup = $this->config['active_group'];
        }
        $this->config['database'] = self::mapConfigToDoctrine(
            self::filterConfigArray($db[$this->activeGroup]));
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    private function createEntityManager() {
        $this->em = EntityManager::create(
            $this->config['database'],
            $this->metadataConfiguration,
            $this->config['event_manager']
        );
    }
}
