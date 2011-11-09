<?php
// enable error reporting
error_reporting( E_ALL | E_STRICT );
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
define('APPLICATION_ENV', 'testing');

date_default_timezone_set('Europe/London');

require_once realpath(dirname(__FILE__)) . "/../application-bootstrap.php";

define('TESTS_PATH', realpath(dirname(__FILE__)));

$_SERVER['SERVER_NAME'] = 'http://localhost';

// start MVC, Session support and bootstrap application
Zend_Layout::startMvc();
Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();
// don't use $application->bootstrap()->run()!
$application = Zend_Registry::get('application');
$application->bootstrap();
$options = $application->getOptions();

// factory database adapter
$db = Zend_Db::factory($options['resources']['db']['adapter'], $options['resources']['db']['params']);

// Register database workflows
// cleanSchemaSetup removes the old test schmea (only tables and views - *not* the database itself) and re-creates tables, views and the fixture
$cleanSchemaSetup = new Bootstrap_Test_Database_Workflow();
$cleanSchemaSetup
	->register(new Bootstrap_Test_Database_Workflow_RemoveSchema($db))
	->register(new Bootstrap_Test_Database_Workflow_Script($options['fixturehelper']['create-tables'], $options['fixturehelper']['mysql-command']))
	->register(new Bootstrap_Test_Database_Workflow_Script($options['fixturehelper']['create-views'], $options['fixturehelper']['mysql-command']));
Zend_Registry::set('cleanSchemaSetup', $cleanSchemaSetup);
$cleanSchemaSetup->execute();

// recreateFixture removes all rows inside the test schema and recreates the fixture
$setUpFixture = new Bootstrap_Test_Database_Workflow('recreate');
$setUpFixture
	->register(new Bootstrap_Test_Database_Workflow_RemoveFixture($db, 'remove fixtures from test database'))
	->register(new Bootstrap_Test_Database_Workflow_Script($options['fixturehelper']['create-fixture'], $options['fixturehelper']['mysql-command'], 'execute fixture script'));
Zend_Registry::set('setUpFixture', $setUpFixture);

