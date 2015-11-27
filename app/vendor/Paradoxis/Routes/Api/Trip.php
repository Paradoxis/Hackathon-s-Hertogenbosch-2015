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
 * Class Trip
 * @package Paradoxis\Routes\Api
 */
class Trip extends Group
{
	/**
	 * Initialize api trip routes
	 * @return void
	 */
	public function initialize()
	{
		// Set the prefix
		$this->setPrefix('/api');

		// Define all routes
		$this->addPost('/trip/getTrips', 'Trip::getTrips');
		$this->addPost('/trip/addBooking', 'Trip::addBooking');
		$this->addPost('/trip/getBookings', 'Trip::getBookings');
	}
}