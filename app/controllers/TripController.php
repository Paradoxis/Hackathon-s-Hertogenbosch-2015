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
use \Paradoxis\Models\Trips;
use \Paradoxis\Http\Response;

/**
 * Class TripController
 * @package Paradoxis\Controllers
 */
class TripController extends ControllerBase
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
	 * Book a specified trip
	 * @param string [token]
	 * @param int [tripId]
	 * @return \Phalcon\Http\Response
	 */
	public function addBookingAction()
	{
		// Valid parameters
		if (
			$this->request->hasPost('token') &&
			$this->request->hasPost('tripId')
		) {

			// Find auth and trip by token/id
			$auth = AuthTokens::findFirstByToken($this->request->getPost('token'));
			$trip = Trips::findFirst($this->request->getPost('tripId'));

			// Validate if auth token exists
			if ($auth == false) {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}

			// Validate if trip exists
			if ($trip == false) {
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Invalid trip id.'
				]);
			}

			// Prepare the booking
			$booking = new Bookings();
			$booking->bkUserId = $auth->users->userId;
			$booking->bkTripId = $trip->tripId;
			$booking->bkDate = date('Y-m-d h:i:s');

			// Give the current person X amount of points
			// Points formula for booking a trip: P = (M * 2.5)
			// [P] = Points
			// [M] = Cost of the trip
			$points = round($trip->tripPrice * 2.5);
			$auth->users->userPoints += $points;

			// Try to save the booking
			if ($booking->save() == false) {
				$this->response->setStatusCode(500, 'Internal server error');
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Unable to save booking.'
				]);
			}

			// Try to save the user points
			if ($auth->save() == false) {
				$this->response->setStatusCode(500, 'Internal server error');
				return $this->reply->asJson([
					'success' => false,
					'message' => 'Unable to save user points.'
				]);
			}

			// Successful booking
			return $this->reply->asJson([
				'success' => true,
				'message' => 'Booking placed successfully.',
				'user' => [
					'pointsAdded' => $points,
					'pointsTotal' => $auth->users->userPoints,
					'pointsOld' => $auth->users->userPoints - $points
				]
			]);

		// Invalid parameters
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}


	/**
	 * Get all bookings related to a user by their authorisation token
	 * @param string [token]
	 * @return \Phalcon\Http\Response
	 */
	public function getBookingsAction()
	{
		if ($this->request->hasPost('token')) {
			if ($auth = AuthTokens::findFirstByToken($this->request->getPost('token'))) {

				// Sort all of the bookings
				$bookings = [];
				foreach(Bookings::findByUserId($auth->authUserId) as $booking) {
					$bookings[] = [
						'id' => $booking->bkId,
						'date' => $booking->bkDate,
						'title' => $booking->trips->tripName
					];
				}

				// Send the bookings as a json response
				return $this->reply->asJson([
					'success' => true,
					'message' => '',
					'bookings' => $bookings
				]);
			} else {
				return $this->reply->withFail(Response::ERROR_INVALID_AUTH);
			}
		} else {
			return $this->reply->withFail(Response::ERROR_INVALID_PARAMS);
		}
	}

	/**
	 * Get all trips in JSON format
	 * @return \Phalcon\Http\Response
	 */
	public function getTripsAction()
	{
		// Fetch trips
		$trips = [];
		foreach(Trips::find() as $trip) {
			$trips[] = [
				'id' => $trip->tripId,
				'name' => $trip->tripName,
				'description' => $trip->tripDescription,
				'location' => $trip->tripLocation,
				'price' => $trip->tripPrice,
				'people' => $trip->tripPeople,
				'dateFrom' => $trip->tripFromDate,
				'dateTo' => $trip->tripToDate
			];
		}

		// Spit out trips
		return $this->reply->asJson([
			'success' => true,
			'message' => '',
			'trips' => $trips
		]);
	}
}