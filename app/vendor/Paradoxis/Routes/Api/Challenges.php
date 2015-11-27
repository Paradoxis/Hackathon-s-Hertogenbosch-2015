<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Routes\Api;

/**
 * Use classes
 * @package Phalcon
 */
use Phalcon\Mvc\Router\Group;

/**
 * Class Challenges
 * @package Paradoxis\Routes\Api
 */
class Challenges extends Group
{
	/**
	 * Initialize api auth routes
	 * @return void
	 */
	public function initialize()
	{
		// Set the prefix
		$this->setPrefix('/api');

		// Define all routes
		$this->addPost('/challenges/addChallenge', 'Challenges::addChallenge');
		$this->addPost('/challenges/getChallenges', 'Challenges::getChallenges');
		$this->addPost('/challenges/startChallenge', 'Challenges::startChallenge');
		$this->addPost('/challenges/completeChallenge', 'Challenges::completeChallenge');
		$this->addPost('/challenges/getChallengeTypes', 'Challenges::getChallengeTypes');
		$this->addPost('/challenges/getUserChallenges', 'Challenges::getUserChallenges');
	}
}