<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class Challenges
 * @package Paradoxis\Models
 */
class Challenges extends \Phalcon\Mvc\Model
{

	/**
	 * Default amount of points given for a challenge
	 * @var integer
	 */
	const DEFAULT_POINTS = 15;

    /**
     * Challenge id
     * @var integer
     */
    public $chaId;

    /**
     * Challenge type id
     * @var integer
     */
    public $chaTypeId;

    /**
     * Challenge name
     * @var string
     */
    public $chaName;

    /**
     * Challenge description
     * @var string
     */
    public $chaDescription;

    /**
     *
     * @var integer
     */
    public $chaPoints;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Challenges');
        $this->belongsTo('chaTypeId', '\Paradoxis\Models\ChallengeTypes', 'ctpId', array('alias' => 'Challengetypes'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'chaId' => 'chaId', 
            'chaTypeId' => 'chaTypeId', 
            'chaName' => 'chaName',
            'chaDescription' => 'chaDescription', 
            'chaPoints' => 'chaPoints'
        );
    }

}
