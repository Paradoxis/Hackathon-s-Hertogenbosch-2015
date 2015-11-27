<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class Users
 * @package Paradoxis\Models
 */
class Users extends \Phalcon\Mvc\Model
{

    /**
     * User id
     * @var integer
     */
    public $userId;

    /**
     * User's first name
     * @var string
     */
    public $userFirstname;

    /**
     * User's last name
     * @var string
     */
    public $userLastname;

    /**
     * User's prefix
     * @var string
     */
    public $userPrefix;

    /**
     * User's phone number
     * @var string
     */
    public $userPhone;

    /**
     * User's email address
     * @var string
     */
    public $userEmail;

    /**
     * User's password (char:40)
     * @var string
     */
    public $userPassword;

    /**
     * User's points
     * @var integer
     */
    public $userPoints;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Users');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'userId' => 'userId', 
            'userFirstname' => 'userFirstname', 
            'userLastname' => 'userLastname', 
            'userPrefix' => 'userPrefix', 
            'userPhone' => 'userPhone', 
            'userEmail' => 'userEmail', 
            'userPassword' => 'userPassword', 
            'userPoints' => 'userPoints'
        );
    }

}
