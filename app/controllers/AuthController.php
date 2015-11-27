<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Controllers;

/**
 * Use classes
 */
use \Paradoxis\Models\Users;
use \Paradoxis\Models\AuthTokens;
use \Paradoxis\Http\Response;
use \Paradoxis\Exception;

/**
 * Class AuthController
 * @package Paradoxis\Controllers
 */
class AuthController extends ControllerBase
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
	 * Add a user to the database
	 * @param $password
	 * @param $email
	 * @param $phone
	 * @param $firstname
	 * @param $lastname
	 * @param string $prefix
	 * @return \Phalcon\Http\Response
	 */
	private function addUser(
		$password,
		$email,
		$phone,
		$firstname,
		$lastname,
		$prefix = ""
	) {
		// Validate email
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
			return $this->reply->asJson([
				'success' => false,
				'message' => "Invalid email address format."
			]);
		}

		// Validate duplicates
		if ($user = Users::findFirst([
			"conditions" => "userEmail = :email:",
			"bind" => [
				"email" => $email
			]
		])) {
			return $this->reply->asJson([
				'success' => false,
				'message' => "User already exists with email."
			]);
		} else {

			// Add user to the database
			$user = new Users();
			$user->userFirstname = $firstname;
			$user->userLastname = $lastname;
			$user->userPrefix = $prefix;
			$user->userPhone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
			$user->userEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
			$user->userPassword = sha1($password.$this->config->database->dbsalt);
			$user->userPoints = 0;

			// Try to save
			if ($user->save()) {
				return $this->reply->asJson([
					'success' => true,
					'message' => 'User added successfully.'
				]);
			} else {
				$this->response->setStatusCode(500, "Internal server error");
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Unable to save user to the database.'
				]);
			}
		}
	}


	/**
	 * Create a token and return the value
	 * @throws \Paradoxis\Exception
	 * @param int $userId
	 * @return string
	 */
	private function addToken($userId) {

		// Validate if the userId exists
		if ($user = Users::findFirst([
			"conditions" => "userId = :id:",
			"bind" => [
				"id" => $userId
			]
		])) {

			// Check if the user is already logged in
			// If so, just return the current token that was assigned to that user
			if ($token = AuthTokens::findFirst([
				"conditions" => "authUserId = :id:",
				"bind" => [
					"id" => $userId
				]
			])) {
				return $token->authToken;
			} else {

				// Create token
				$token = sha1(mt_rand().time().mt_rand());

				// Create a new token and insert it into the database
				$auth = new AuthTokens();
				$auth->authUserId = $userId;
				$auth->authToken = $token;

				// Try to save the token
				if ($auth->save()) {
					return $token;
				} else {
					$this->response->setStatusCode(500, "Internal server error");
					throw new Exception("Unable to save authorisation token.");
				}
			}
		} else {
			throw new Exception(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Register a new user
	 * @param string [firstname]
	 * @param string [lastname]
	 * @param string [email]
	 * @param string [password]
	 * @param string|int [phone]
	 * @param string [prefix] (optional)
	 * @param int|bool [hash-password] (optional)
	 * @return \Phalcon\Http\Response
	 */
	public function registerAction()
	{
		if (
			$this->request->hasPost('firstname') &&
			$this->request->hasPost('lastname') &&
			$this->request->hasPost('email') &&
			$this->request->hasPost('password') &&
			$this->request->hasPost('phone')
		) {
			// Check for voluntary MD5 hashing
			// The android app hashes the password by default using MD5
			if ($this->request->hasPost('hash-password') && $this->request->getPost('hash-password')) {
				$password = md5($this->request->getPost('password'));
			} else {
				$password = $this->request->getPost('password');
			}

			// Return the result of the addUser method
			return $this->addUser(
				$password,
				$this->request->getPost('email'),
				$this->request->getPost('phone'),
				$this->request->getPost('firstname'),
				$this->request->getPost('lastname'),
				$this->request->getPost('prefix', '')
			);
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Log the user in
	 * @param string [email]
	 * @param string [password]
	 * @param int|bool [hash-password] (optional)
	 * @return \Phalcon\Http\Response
	 */
	public function loginAction()
	{
		// Check the parameters
		if (
			$this->request->hasPost('email') &&
			$this->request->hasPost('password')
		) {

			// Check for voluntary MD5 hashing
			// The android app hashes the password by default using MD5
			if ($this->request->hasPost('hash-password') && $this->request->getPost('hash-password')) {
				$password = md5($this->request->getPost('password'));
			} else {
				$password = $this->request->getPost('password');
			}

			// Find a user in the database
			if ($user = Users::findFirst([
				"conditions" => "userEmail = :email: AND userPassword = :password:",
				"bind" => [
					"email" => $this->request->getPost('email'),
					"password" => sha1($password.$this->config->database->dbsalt)
				]
			])) {
				return $this->reply->asJson([
					'success' => true,
					'message' => 'Login successful.',
					'user' => [
						'firstname' => $user->userFirstname,
						'lastname' => $user->userLastname,
						'prefix' => $user->userPrefix,
						'phone' => $user->userPhone,
						'email' => $user->userEmail,
						'points' => $user->userPoints,
						'token' => $this->addToken($user->userId)
					]
				]);
			} else {
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Invalid username or password.'
				]);
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}


	/**
	 * Log the user out
	 * Must be logged in first
	 * @param string [token]
	 * @return \Phalcon\Http\Response
	 */
	public function logoutAction()
	{
		if ($this->request->hasPost('token')) {
			if ($token = AuthTokens::findFirst([
				"conditions" => "authToken = :token:",
				"bind" => [
					"token" => $this->request->getPost('token')
				]
			])) {
				if ($token->delete()) {
					return $this->reply->asJson([
						'success' => true,
						'message' => 'You have been successfully logged out.'
					]);
				} else {
					$this->response->setStatusCode(500, "Internal server error");
					return $this->reply->asJson([
						'success' => false,
						'message' => 'Unable to remove authorisation token.'
					]);
				}
			} else {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}
}