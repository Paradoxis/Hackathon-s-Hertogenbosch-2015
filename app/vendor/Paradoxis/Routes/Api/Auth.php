<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Routes\Api;

/**
 * Use classes
 * @package Phalcon
 */
use Phalcon\Mvc\Router\Group;

/**
 * Class Auth
 * @package Paradoxis\Routes\Api
 */
class Auth extends Group
{
	/**
	 * Initialize api auth routes
	 * @return void
	 */
	public function initialize()
	{
		// Set the prefix
		$this->setPrefix('/api');

		// Define all routes
		$this->addPost('/auth/login', 'Auth::login');
		$this->addPost('/auth/logout', 'Auth::logout');
		$this->addPost('/auth/register', 'Auth::register');
	}
}