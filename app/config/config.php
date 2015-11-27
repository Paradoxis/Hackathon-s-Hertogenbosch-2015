<?php

/**
 * Phalcon configuration
 * @type \Phalcon\Config
 */
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => '',
        'password'    => '',
        'dbname'      => 'Vacation',
		'dbsalt'	  => ''
    ),
    'application' => array(
		'controllersDir' 	 => __DIR__ . '/../../app/controllers/',
		'modelsDir'      	 => __DIR__ . '/../../app/models/',
		'viewsDir'       	 => __DIR__ . '/../../app/views/',
        'vendorDir'      	 => __DIR__ . '/../../app/vendor/',
        'cacheDir'       	 => __DIR__ . '/../../app/cache/',
        'baseUri'        	 => '/',
    )
));
