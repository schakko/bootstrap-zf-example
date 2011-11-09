<?php
require_once realpath(dirname(__FILE__)) . "/../application-bootstrap.php";

// Start MVC
Zend_Layout::startMvc();

// Start application
Zend_Registry::get('application')->bootstrap()
            ->run();
