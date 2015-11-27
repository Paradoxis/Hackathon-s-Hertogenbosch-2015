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
use \Paradoxis\Models\Bookings;
use \Paradoxis\Models\Challenges;
use \Paradoxis\Models\ChallengeTypes;
use \Paradoxis\Models\TripChallenges;
use \Paradoxis\Models\Trips;
use \Paradoxis\Models\UserChallenges;
use \Paradoxis\Http\Response;

/**
 * Class ChallengesController
 * @package Paradoxis\Controllers
 */
class ChallengesController extends ControllerBase
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
	 * Get a list of all available challenge type key/value pairs
	 * @return \Phalcon\Http\Response
	 */
	public function getChallengeTypesAction()
	{
		// Get the challenge types
		$types = [];
		foreach(ChallengeTypes::find() as $type) {
			$types[] = $type->ctpName;
		}

		// Dump the types to the user
		return $this->reply->asJson([
			'success' => true,
			'message' => "",
			'types' => $types
		]);
	}

	/**
	 * Get a list of all challenges
	 * Limited to 50 results per time.
	 * The app can request a field to by specifying the [start] post field with an integer
	 * @param int [start] (optional)
	 * @return \Phalcon\Http\Response
	 */
	public function getChallengesAction()
	{
		// Start index (50 limit per time)
		if ($this->request->hasPost('start') && is_numeric($this->request->getPost('start'))) {
			$start = $this->request->getPost('start');
		} else {
			$start = 0;
		}

		// Fetch all challenges
		$challenges = [];
		foreach(Challenges::find([
			"order" => "chaId DESC",
			"limit" => ["number" => 25, "offset" => $start]
		]) as $challenge) {
			$challenges[] = [
				'id' => $challenge->chaId,
				'type' => $challenge->Challengetypes->ctpName,
				'name' => $challenge->chaName,
				'description' => $challenge->chaDescription,
				'points' => $challenge->chaPoints
			];
		}

		// Throw the challenges at the user
		return $this->reply->asJson([
			'success' => true,
			'message' => '',
			'challenges' => $challenges
		]);
	}

	/**
	 * Get all completed/active user challenges
	 * Sorted by active ones on top, then by newest first
	 * @param string [token]
	 * @return \Phalcon\Http\Response
	 */
	public function getUserChallengesAction()
	{
		if ($this->request->hasPost('token')) {
			if ($auth = AuthTokens::findFirstByToken($this->request->getPost('token'))) {

				// Fetch challenges
				$challenges = [];
				foreach(UserChallenges::find([
					"conditions" => "ucUserId = :userId:",
					"bind" => [
						"userId" => $auth->authUserId
					],
					"order" => "ucCompleted DESC, ucId ASC"
				]) as $challenge) {
					$challenges[] = [
						'id' => $challenge->ucId,
						'challengeId' => $challenge->ucChaId,
						'completed' => (bool) $challenge->ucCompleted,
						'dateStart' => $challenge->ucDateStart,
						'dateEnd' => $challenge->ucDateEnd
					];
				}

				// Return the result
				return $this->reply->asJson([
					'success' => true,
					'message' => '',
					'challenges' => $challenges
				]);
			} else {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Start a challenge
	 * @param int [id]
	 * @param string [token]
	 * @return \Phalcon\Http\Response
	 */
	public function startChallengeAction()
	{
		if (
			$this->request->hasPost('id') &&
			$this->request->hasPost('token') &&
			is_numeric($this->request->getPost('id'))
		) {

			// Fetch related data
			$auth = AuthTokens::findFirstByToken($this->request->getPost('token'));
			$challenge = Challenges::findFirst($this->request->getPost('id'));

			// Validate authentication
			if ($auth == false) {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}

			// Validate challenge
			if ($challenge == false) {
				return $this->reply->withFail('Invalid challenge id.');
			}

			// Check if user is allowed to do challenges
			if (Bookings::hasActiveBookings ($auth->authUserId) == false) {
				return $this->reply->withFail('User has no active booked trips.');
			}

			// Validate if the user hasn't finished the challenge before
			if ($userChallenge = UserChallenges::findFirst([
				"conditions" => "ucUserId = :userId: AND ucChaId = :chaId:",
				"bind" => [
					"userId" => $auth->authUserId,
					"chaId" => $challenge->chaId
				]
			])) {
				if ($userChallenge->ucCompleted) {
					return $this->reply->withFail("Unable to start user challenge, user has already finished it.");
				} else {
					return $this->reply->withFail("Unable to start user challenge, user has already started it.");
				}
			} else {

				// Create a new user challenge
				$userChallenge = new UserChallenges();
				$userChallenge->ucUserId = $auth->authUserId;
				$userChallenge->ucChaId = $challenge->chaId;
				$userChallenge->ucCompleted = 0;
				$userChallenge->ucDateStart = date("Y-m-d h:i:s");
				$userChallenge->ucDateEnd = null;

				// Try to save the user challenge
				if ($userChallenge->save()) {
					return $this->reply->asJson([
						'success' => true,
						'message' => "Successfully started user challenge."
					]);
				} else {
					$this->response->setStatusCode(500, 'Internal server error');
					return $this->reply->withFail("Unable to start user challenge.");
				}
			}
  		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Complete a challenge
	 * @param int [id]
	 * @param string [token]
	 * @return \Phalcon\Http\Response
	 */
	public function completeChallengeAction()
	{
		if (
			$this->request->hasPost('id') &&
			$this->request->hasPost('token') &&
			is_numeric($this->request->getPost('id'))
		) {

			// Fetch related data
			$auth = AuthTokens::findFirstByToken($this->request->getPost('token'));
			$challenge = Challenges::findFirst($this->request->getPost('id'));

			// Validate authentication
			if ($auth == false) {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}

			// Validate challenge
			if ($challenge == false) {
				return $this->reply->withFail('Invalid challenge id.');
			}

			// Check if the user has started the challenge in the first place
			if ($userChallenge = UserChallenges::findFirst([
				"conditions" => "ucUserId = :userId: AND ucChaId = :chaId:",
				"bind" => [
					"userId" => $auth->authUserId,
					"chaId" => $challenge->chaId
				]
			])) {
				if ($userChallenge->ucCompleted == 0) {
					$userChallenge->ucCompleted = 1;
					$userChallenge->ucDateEnd = date('Y-m-d h:i:s');
					$auth->users->userPoints += $challenge->chaPoints;

					// Try to save the user points
					if ($auth->save() == false) {
						return $this->reply->withFail("Unable to complete challenge, failed to add user points.");
					}

					// Try to save the challenge
					if ($userChallenge->save()) {
						return $this->reply->asJson([
							'success' => true,
							'message' => "Successfully completed challenge.",
							'user' => [
								'pointsAdded' => $challenge->chaPoints,
								'pointsTotal' => $auth->users->userPoints,
								'pointsOld' => $auth->users->userPoints - $challenge->chaPoints
							]
						]);
					} else {
						$this->response->setStatusCode(500, 'Internal server error');
						return $this->reply->withFail("Unable to complete challenge.");
					}
				} else {
					return $this->reply->withFail("Unable to complete challenge, user has already completed it.");
				}
			} else {
				return $this->reply->withFail("Unable to complete challenge, user has not started it yet.");
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Add a challenge which can be approved later for additional user points.
	 * Optionally, you can bind it to be a location based challenge, by specifying a tripId.
	 * This will add a row to the TripChallenges table to create a many-to-many relationship.
	 * @param string [token]
	 * @param string [name]
	 * @param string [description]
	 * @param int [typeId] @see getChallengesAction()
	 * @param int [tripId] (optional)
	 * @return \Phalcon\Http\Response
	 */
	public function addChallengeAction()
	{
		if (
			$this->request->hasPost('token') &&
			$this->request->hasPost('name') &&
			$this->request->hasPost('typeId') &&
			$this->request->hasPost('description') &&
			is_numeric($this->request->getPost('typeId'))
		) {
			if ($auth = AuthTokens::findFirstByToken($this->request->getPost('token'))) {

				// Check if the challenge type id exists
				if (ChallengeTypes::findFirst($this->request->getPost('typeId')) == false) {
					return $this->reply->withFail('Invalid challenge type id supplied.');
				}

				// Check if trip id exists
				if ($this->request->hasPost('tripId') && is_numeric($this->request->getPost('tripId'))) {
					if (Trips::findFirst($this->request->getPost('tripId')) == false) {
						return $this->reply->withFail('Invalid trip id supplied.');
					} else {
						$tripChallenges = new TripChallenges();
						$tripChallenges->tcTripId = abs((int) $this->request->getPost('tripId'));
					}
				}

				// Check for a duplicate challenge name
				if (Challenges::findFirst([
					"conditions" => "chaName = :name:",
					"bind" => [
						"name" => $this->request->getPost('name')
					]
				])) {
					return $this->reply->withFail('Duplicate challenge name already exists.');
				}

				// Create new challenge
				$challenge = new Challenges();
				$challenge->chaTypeId = $this->request->getPost('typeId');
				$challenge->chaName = $this->request->getPost('name');
				$challenge->chaDescription = $this->request->getPost('description');
				$challenge->chaPoints = Challenges::DEFAULT_POINTS;

				// Attempt to save the challenge
				if ($challenge->save()) {
					if (isset($tripChallenges)) {
						if ($tripChallenges->tcChaId = $challenge->chaId && $tripChallenges->save()) {
							return $this->reply->asJson([
								'success' => true,
								'message' => 'Trip based challenge saved successfully.'
							]);
						} else {
							$challenge->delete();
							return $this->reply->withFail('Failed to save trip with challenge relationship.');
						}
					} else {
						return $this->reply->asJson([
							'success' => true,
							'message' => 'Challenge saved successfully.'
						]);
					}
				} else {
					$this->response->setStatusCode(500, 'Internal server error');
					return $this->reply->withFail("Unable to save challenge.");
				}
			} else {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}
}