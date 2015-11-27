<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Http;

/**
 * Class Response
 * @package Paradoxis\Http
 */
class Response {

	/**
	 * Error messages
	 * @var string
	 */
	const ERROR_INVALID_PARAMS = 'Invalid parameters.';
	const ERROR_INVALID_AUTH = 'Invalid authorisation token';

	/**
	 * Send a json response
	 * @param mixed $data
	 * @return \Phalcon\Http\Response
	 */
	public function asJson($data) {
		$response = new \Phalcon\Http\Response();
		$response->setContentType('application/json', 'UTF-8');
		$response->setContent(json_encode($data));
		return $response;
	}

	/**
	 * Automatically return a json response with a pre-written error
	 * @param string $message [const]
	 * @return \Phalcon\Http\Response
	 */
	public function withFail($message) {
		return $this->asJson([
			'success' => false,
			'message' => $message
		]);
	}
}