<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Twig extends Twig_Environment {

    /** @var CI_Controller $CI */
    protected $CI;
    /** @var  array $config*/
    protected $config = array();
    /** var array $paths */
    protected $paths = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->loadConfig();
        $loader = new Twig_Loader_Filesystem($this->paths);
        parent::__construct($loader, $this->config);

        $this->addGlobal('environment', ENVIRONMENT);
        $this->addGlobal('ci_version', CI_VERSION);

        if ($this->config['benchmark']) {
            $this->addGlobal(
                'elapsed_time',
                $this->CI->benchmark->elapsed_time(
                    'total_execution_time_start',
                    'total_execution_time_end')
            );
            $this->addGlobal('memory', round(memory_get_usage() / 1024 / 1024, 2).'MB');
        } else {
            // in case of usage
            $this->addGlobal('elapsed_time', 'null');
            $this->addGlobal('memory', 'null');
        }

    }

    protected function loadConfig() {
        $this->CI->config->load('twig', TRUE, TRUE);
        $this->config = $this->CI->config->item('twig');

        $this->paths = $this->config['template_dirs'];
        unset($this->config['template_dirs']);
    }

    public function getConfig() {
        return $this->config;
    }

    public function getTemplateDirs() {
        return $this->paths;
    }
}
