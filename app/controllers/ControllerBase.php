<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis\Controllers;

/**
 * Use classes
 */
use \Phalcon\Mvc\Controller;
use \Paradoxis\Http\Response;

/**
 * Class ControllerBase
 * @package Paradoxis\Controller
 */
class ControllerBase extends Controller
{
	/**
	 * Custom response class
	 * @var \Paradoxis\Http\Response;
	 */
	protected $reply;

	/**
	 * Initialize controller base
	 * @return void
	 */
	public function onConstruct()
	{
		$this->reply = new Response();
	}
}
