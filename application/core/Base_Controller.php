<?php

/**
 * Class Base_Controller
 * @property Twig $twig
 * @property Doctrine $doctrine
 */
class Base_Controller extends CI_Controller
{
    /** @var \Doctrine\ORM\EntityManager $em */
    protected $em;
    /** @var \DebugBar\StandardDebugBar $debugBar  */
    protected $debugBar;
    /** @var  bool $debuggerEnabled */
    protected $debuggerEnabled = false;

    function __construct() {
        parent::__construct();
        $this->load->library('Twig', null, 'twig');
        $this->load->library('Doctrine', true, 'doctrine');
        $this->em = $this->doctrine->getEntityManager();
        $this->setDebugging();
    }

    /**
     * Sets up Php Debug Bar
     * @throws \DebugBar\DebugBarException
     */
    private function setDebugging() {
        // check debug
        $this->load->config('php_debug_bar', true, true);
        $debugConfig = $this->config->item('php_debug_bar');
        $this->twig->addGlobal('debug', $debugConfig['init_debug_bar']);
        $this->debuggerEnabled = $debugConfig['init_debug_bar'];
        if ($debugConfig['init_debug_bar']) {
            $this->load->helper('url');
            $this->debugBar = new \DebugBar\StandardDebugBar();
            $this->twig->addGlobal(
                'debugger',
                $this->debugBar->getJavascriptRenderer()->setBaseUrl(base_url() . 'debug/')
            );

            // attach doctrine hook
            if ($debugConfig['doctrine_hook']) {
                $stack =  $this->doctrine->getDebugStack();
                if ($stack) {
                    $this->debugBar->addCollector(
                        new DebugBar\Bridge\DoctrineCollector($stack)
                    );
                }
            }
        }
    }

    protected function _debug($message = '', $logLevel = \Psr\Log\LogLevel::DEBUG) {
        if ($this->debuggerEnabled) {
            /** @var DebugBar\DataCollector\MessagesCollector $messagesCollector */
            $messagesCollector = $this->debugBar["messages"];
            $messagesCollector->addMessage($message, $logLevel);
        }
    }
}
