<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class UserChallenges
 * @package Paradoxis\Models
 */
class UserChallenges extends \Phalcon\Mvc\Model
{

    /**
     * User challenge id
     * @var integer
     */
    public $ucId;

    /**
     * Linked user id
     * @var integer
     */
    public $ucUserId;

    /**
     * Linked challenge id
     * @var integer
     */
    public $ucChaId;

    /**
     * Completed user challenge?
     * @var string
     */
    public $ucCompleted;

    /**
     * Date started user challenge
     * @var string
     */
    public $ucDateStart;

    /**
     * Date finished user challenge
	 * Note: cannot be filled ub uf ucCompleted is 0!
     * @var string
     */
    public $ucDateEnd;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('UserChallenges');
        $this->belongsTo('ucChaId', '\Paradoxis\Models\Challenges', 'chaId', array('alias' => 'Challenges'));
        $this->belongsTo('ucUserId', '\Paradoxis\Models\Users', 'userId', array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ucId' => 'ucId', 
            'ucUserId' => 'ucUserId', 
            'ucChaId' => 'ucChaId', 
            'ucCompleted' => 'ucCompleted', 
            'ucDateStart' => 'ucDateStart', 
            'ucDateEnd' => 'ucDateEnd'
        );
    }

}
