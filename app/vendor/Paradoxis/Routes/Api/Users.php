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
 * Class Users
 * @package Paradoxis\Routes\Api
 */
class Users extends Group
{
	/**
	 * Initialize api user routes
	 * @return void
	 */
	public function initialize()
	{
		// Set the prefix
		$this->setPrefix('/api');

		// Define all routes
		$this->addPost('/users/getProfile', 'Users::getProfile');
	}
}