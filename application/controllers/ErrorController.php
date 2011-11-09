<?php

class ErrorController extends Bootstrap_Controller_Base
{

	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');

		if (!$errors) {
			$this->view->message = 'You have reached the error page';
			return;
		}

		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
			$this->view->message = 'Application error';
			break;
		}

		$code = $errors->exception->getCode();

		if (!$code) {
			$code = 500;
		}

		try {
			$this->getResponse()->setHttpResponseCode($code);
		} catch (Exception $e) {
			// Ignore invalid HTTP response code exception
		}


		// AJAX/JSON request
		if ($this->getRequest()->isXmlHttpRequest() || $this->isJsonRequest()) {
			$trace = null;

			// we are in development mode, so throw stacktrace for further investigation
			if (APPLICATION_ENV === 'development') {
				$trace = $errors->exception->getTrace();
			}

			$this->sendJsonResponse(array('error' => $errors->exception->getMessage(), 'trace' => $trace), $code);
		}

		// Log exception, if logger available
		// 		if ($log = $this->getLog()) {
		// 			$log->crit($this->view->message, $errors->exception);
		// 		}


		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}

		$this->view->request   = $errors->request;
	}

	public function getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');

		if (!$bootstrap) {
			$r = new Zend_Log();
			$r->addWriter(new Zend_Log_Writer_Syslog());
			return $r;
		}

		if (!$bootstrap->hasResource('Log')) {
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}


}

