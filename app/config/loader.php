<?php

/**
 * Create a new loader
 */
$loader = new \Phalcon\Loader();

/**
 * We're registering a set of namespaces from the configuration files
 * This prevents buggy behaviour, and allows us to create sub-controllers.
 */
$loader->registerNamespaces(
	array(
		'Paradoxis\Controllers' => $config->application->controllersDir,
		'Paradoxis\Models' => $config->application->modelsDir
	)
);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->vendorDir
    )
)->register();