<?php

if (version_compare(PHP_VERSION, '5.4', '<')) return;

$application = 'application';
$modules = 'modules';
$system = 'system';

/**
 * End of standard configuration! Changing any of the code below should only be
 * attempted by those with a working knowledge of Kohana internals.
 */
//The default extension of resource files. If you change this, all resources must be renamed to use the new extension.
define('EXT', '.php');
// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)) . '/');

// Make the application relative to the docroot, for symlink'd index.php
if (!is_dir($application) AND is_dir(DOCROOT . $application))
    $application = DOCROOT . $application;

// Make the modules relative to the docroot, for symlink'd index.php
if (!is_dir($modules) AND is_dir(DOCROOT . $modules))
    $modules = DOCROOT . $modules;

// Make the system relative to the docroot, for symlink'd index.php
if (!is_dir($system) AND is_dir(DOCROOT . $system))
    $system = DOCROOT . $system;

// Define the absolute paths for configured directories
define('APPPATH', realpath($application) . '/');
define('MODPATH', realpath($modules) . '/');
define('SYSPATH', realpath($system) . '/');
define('DEV_MODPATH', realpath(DOCROOT . '../dev-modules/') . '/');

// Clean up the configuration vars
unset($application, $modules, $system);

// Load the installation check
if (file_exists('install' . EXT)) {
    return include 'install' . EXT;
}

// Bootstrap the application
require APPPATH . 'bootstrap' . EXT;

if (PHP_SAPI == 'cli') { // Try and load minion
    class_exists('Minion_Task') OR die('Please enable the Minion module for CLI support.');
    set_exception_handler(array('Minion_Exception', 'handler'));

    Minion_Task::factory(Minion_CLI::options())->execute();
} else {
    /**
     * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
     * If no source is specified, the URI will be automatically detected.
     */

    //handle main request.
    //Helper_Bootstrap auto provide sub_request_handlers to handle 404 error by other modules.
    echo Kohana::executeRequest();
}
