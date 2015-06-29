<?php

class Base_Controller extends CI_Controller
{
    protected $twig = null;

    function __construct($debug = null)
    {
        parent::__construct();

        $loader = new Twig_Loader_Filesystem(APPPATH . 'views');
        $this->twig = new Twig_Environment($loader, array(
//            'cache' => '/path/to/compilation_cache',
        ));

    }

}