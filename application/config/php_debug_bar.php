<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * !!!NOTE:!!!
 *      THIS IS ONLY INTENDED FOR DEBUGGING PURPOSES.
 *      PLEASE DISABLE THIS IN PRODUCTION ENVIRONMENT SINCE IT POSES A SECURITY RISK.
 *
 * Available options:
 *
 * * init_debug_bar: Show/Hide Php Debug Bar (default: false).
 *
 * * doctrine_hook: Attach Debug Bar to Doctrine; note that for this
 *                  to work Doctrine bust be initialized in dev_mode
 *                  see config/doctrine.php (default: true)
 */

$config = array(
    'init_debug_bar'    => true,
    'doctrine_hook'     => true,
);
