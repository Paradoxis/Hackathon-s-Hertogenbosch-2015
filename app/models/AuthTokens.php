<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Models;

/**
 * Class AuthTokens
 * @package Paradoxis\Models
 */
class AuthTokens extends \Phalcon\Mvc\Model
{

    /**
     * Authorisation token id
     * @var integer
     */
    public $authId;

    /**
     * Linked user id
     * @var integer
     */
    public $authUserId;

    /**
     * Authorisation token (40 chars)
     * @var string
     */
    public $authToken;

	/**
	 * Find auth by token
	 * @param string $token
	 * @return object
	 */
	public static function findFirstByToken($token) {
		return self::findFirst([
			"conditions" => "authToken = :token:",
			"bind" => [
				"token" => $token
			]
		]);
	}

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('AuthTokens');
        $this->belongsTo('authUserId', '\Paradoxis\Models\Users', 'userId', array('alias' => 'Users'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'authId' => 'authId', 
            'authUserId' => 'authUserId', 
            'authToken' => 'authToken'
        );
    }
}
