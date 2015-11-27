<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Controllers;

/**
 * Class IndexController
 * @package Paradoxis\Controllers
 */
class IndexController extends ControllerBase
{
	/**
	 * Index action
	 * @return void
	 */
    public function indexAction()
    {
		// The default homepage is currently empty, thus forbidden
		$this->response->setStatusCode(403, "Forbidden");

		// @todo DELETE THIS IF THE SITE GOES UP FOR REAL, THIS IS FOR DEMO USE ONLY!!!!#!@#!#
		$this->view->pick('form');
    }

	/**
	 * 404 Not Found
	 * @return void
	 */
	public function notFoundAction()
	{
		$this->response->setStatusCode(403, 'Forbidden');
	}
}

