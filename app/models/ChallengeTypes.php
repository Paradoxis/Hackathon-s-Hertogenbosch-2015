<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class ChallengeTypes
 * @package Paradoxis\Models
 */
class ChallengeTypes extends \Phalcon\Mvc\Model
{

    /**
     * Challenge type id
     * @var integer
     */
    public $ctpId;

    /**
     * Challenge type name
     * @var string
     */
    public $ctpName;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('ChallengeTypes');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ctpId' => 'ctpId', 
            'ctpName' => 'ctpName'
        );
    }

}
