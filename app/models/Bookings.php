<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Use classes
 */
use \Phalcon\Mvc\Model\Query\Builder;

/**
 * Class Bookings
 * @package Paradoxis\Models
 */
class Bookings extends \Phalcon\Mvc\Model
{

    /**
     * Booking id
     * @var integer
     */
    public $bkId;

    /**
     * Linked trip id
     * @var integer
     */
    public $bkTripId;

    /**
     * Linked user id
     * @var integer
     */
    public $bkUserId;

    /**
     * Booking date
     * @var string
     */
    public $bkDate;

	/**
	 * Find first row by user id
	 * @param int $id
	 * @return object
	 */
	public static function findByUserId($id) {
		return self::find([
			"conditions" => "bkUserId = :id:",
			"bind" => [
				"id" => abs((int) $id)
			],
			"order" => "bkDate DESC"
		]);
	}

	/**
	 * Check if a user is currently during their booked holliday peroid
	 * or if they're currently in their time-out period.
	 * @param int $userId
	 * @return bool
	 */
	public static function hasActiveBookings ($userId)
	{
		return true; //@todo temp disabled
		$query = new Builder();
		$query->columns("count(bkId) AS bookings");
		$query->from('Bookings, Trips');
		$query->where("bkUserId = :userId:");
		$query->andWhere("CURDATE() > tripFromDate");
		$query->andWhere("CURDATE() < DATE_ADD(tripFromDate, INTERVAL 2 WEEK)");
		$result = $query->getQuery()->execute(["userId" => $userId])->bookings;
		return ($result > 0);
	}

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Bookings');
        $this->belongsTo('bkTripId', '\Paradoxis\Models\Trips', 'tripId', array('alias' => 'Trips'));
        $this->belongsTo('bkUserId', '\Paradoxis\Models\Users', 'userId', array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'bkId' => 'bkId', 
            'bkTripId' => 'bkTripId', 
            'bkUserId' => 'bkUserId', 
            'bkDate' => 'bkDate'
        );
    }

}
