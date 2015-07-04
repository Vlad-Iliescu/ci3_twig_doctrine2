<?php
/**
 * Doctrine configuration
 *
 * By default doctrine utilizes CI's database connectivity array. To specify
 * another configuration simply add new configuration array to the config/database.php
 * file and load another group;
 *
 * Available options.
 *
 *  * dev_mode: Set to TRUE to disable caching while you develop; If true caching is done
 *              in memory with the ArrayCache. Proxy objects are recreated on every request.
 *              If is false, check for Caches in the order APC, Xcache, Memcache (127.0.0.1:11211),
 *              Redis (127.0.0.1:6379) unless $cache is passed as argument. Also set then proxy
 *              classes have to be explicitly created through the command line. (default: true)
 *
 *  * active_group: set to null to use CI defined $active_group or define other group to user as string;
 *
 *  * metadata_type: Choose what metadata driver should doctrine use: options are 'annotation', 'xml', 'yml'
 *                   (default: 'annotation')
 *
 *  * metadata_path: array of paths where doctrine should look for metadata. If driver is 'annotation' this
 *                   should point to the Entity dir.
 *
 *  * proxy_dir: Where doctrine should generate proxy classes. set to null to use OS temporary files path.
 *
 *  * cache: Enable cache. If dev_mode is true and cache is null check for Caches in the order APC, Xcache,
 *           Memcache (127.0.0.1:11211), Redis (127.0.0.1:6379) or fallback to ArrayCache. Also setting to
 *           null enables ArrayCache. Must be an Instance of \Doctrine\Common\Cache. (default: null(ArrayCache))
 *
 *  * event_manager: Add your own custom event manager or leave blank for new EventManager()
 */

$config = array(
    'dev_mode'              => true,
    'active_group'          => null,
    'metadata_type'         => 'annotation',
    'metadata_path'         => array(APPPATH . 'models'),
    'proxy_dir'             => APPPATH . 'cache',
    'cache'                 => null,
    'event_manager'         => null,
);
