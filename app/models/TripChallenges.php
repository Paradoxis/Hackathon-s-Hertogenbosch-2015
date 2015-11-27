<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class TripChallenges
 * @package Paradoxis\Models
 */
class TripChallenges extends \Phalcon\Mvc\Model
{

    /**
     * Trip challenges id
     * @var integer
     */
    public $tcId;

    /**
     * Linked trip id
     * @var integer
     */
    public $tcTripId;

    /**
     * Linked challenge id
     * @var integer
     */
    public $tcChaId;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('TripChallenges');
        $this->belongsTo('tcChaId', '\Paradoxis\Models\Challenges', 'chaId', array('alias' => 'Challenges'));
        $this->belongsTo('tcTripId', '\Paradoxis\Models\Trips', 'tripId', array('alias' => 'Trips'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tcId' => 'tcId', 
            'tcTripId' => 'tcTripId', 
            'tcChaId' => 'tcChaId'
        );
    }
}