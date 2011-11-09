<?php
// application-bootstrap.php is shared by application and tests so we don't have two duplicate bootstrap files
define('ROOT_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define librarty path
define('LIBRARY_PATH', ROOT_PATH . '/library');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIBRARY_PATH),
	// add bootstrap-zf to library path (git submodule)
	realpath(LIBRARY_PATH . '/bootstrap-zf'),
    get_include_path(),
)));

// Define path to addendum
define('ADDENDUM_PATH', LIBRARY_PATH . '/addendum/annotations.php');

/** Zend_Loader_Autoloader */
require_once "Zend/Loader/Autoloader.php";
$instance = Zend_Loader_Autoloader::getInstance();
// Register Zend Framework
Zend_Loader_Autoloader::getInstance()->registerNamespace('Zend_');
// Register bootstrap-zf
Zend_Loader_Autoloader::getInstance()->registerNamespace('Bootstrap_');
// Register our own application namespace "Exampleapp" (in LIBRARY_PATH/Exampleapp)
Zend_Loader_Autoloader::getInstance()->registerNamespace('Exampleapp_');


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// register application
Zend_Registry::set('application', $application);

