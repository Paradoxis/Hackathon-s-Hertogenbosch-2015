<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Controllers;

/**
 * Use classes
 */
use \Paradoxis\Models\AuthTokens;
use \Paradoxis\Models\Users;
use \Paradoxis\Http\Response;

/**
 * Class UserController
 * @package Paradoxis\Controllers
 */
class UsersController extends ControllerBase
{
	/**
	 * Constructor
	 * @return void
	 */
	public function initialize()
	{
		// Reserved space for constructor
	}

	/**
	 * Get a profile based on an authorisation token
	 * @param string [token] OR int [id]
	 * @return \Phalcon\Http\Response
	 */
	public function getProfileAction()
	{
		// Find own profile by authorisation token
		if ($this->request->hasPost('token')) {
			if ($auth = AuthTokens::findFirstByToken($this->request->getPost('token'))) {
				return $this->reply->asJson([
					'success' => true,
					'message' => '',
					'user' => [
						'firstname' => $auth->users->userFirstname,
						'lastname' => $auth->users->userLastname,
						'prefix' => $auth->users->userPrefix,
						'phone' => $auth->users->userPhone,
						'email' => $auth->users->userEmail,
						'points' => $auth->users->userPoints,
					]
				]);
			} else {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}

		// Find another profile by user id
		} else if (
			$this->request->hasPost('id') &&
			is_numeric($this->request->getPost('id'))
		) {
			if ($user = Users::findFirst($this->request->getPost('id'))) {
				return $this->reply->asJson([
					'success' => true,
					'message' => '',
					'user' => [
						'firstname' => $user->userFirstname,
						'lastname' => $user->userLastname,
						'prefix' => $user->userPrefix,
						'points' => $user->userPoints,
					]
				]);
			} else {
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Invalid user id.'
				]);
			}

		// Invalid parameters
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}
}