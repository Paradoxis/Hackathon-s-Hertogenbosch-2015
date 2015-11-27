<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class Trips
 * @package Paradoxis\Models
 */
class Trips extends \Phalcon\Mvc\Model
{

    /**
     * Trip id
     * @var integer
     */
    public $tripId;

    /**
     * Trip name
     * @var string
     */
    public $tripName;

    /**
     * Trip description
     * @var string
     */
    public $tripDescription;

    /**
     * Trip location
     * @var string
     */
    public $tripLocation;

    /**
     * Trip price
     * @var integer
     */
    public $tripPrice;

	/**
	 * Amount of people that are part of the trip
	 * @var int
	 */
	public $tripPeople;

    /**
     * Trip date start
     * @var string
     */
    public $tripFromDate;

    /**
     * Trip date end
     * @var string
     */
    public $tripToDate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Trips');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tripId' => 'tripId', 
            'tripName' => 'tripName', 
            'tripDescription' => 'tripDescription', 
            'tripLocation' => 'tripLocation',
			'tripPrice' => 'tripPrice',
			'tripPeople' => 'tripPeople',
			'tripFromDate' => 'tripFromDate',
            'tripToDate' => 'tripToDate'
        );
    }
}