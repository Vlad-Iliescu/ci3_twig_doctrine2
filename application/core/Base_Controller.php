<?php

/**
 * Class Base_Controller
 * @property Twig $twig
 * @property Doctrine $doctrine
 */
class Base_Controller extends CI_Controller
{

    function __construct($debug = null)
    {
        parent::__construct();
        $this->load->library('Twig', null, 'twig');
        $this->load->library('Doctrine', true, 'doctrine');
    }

}
