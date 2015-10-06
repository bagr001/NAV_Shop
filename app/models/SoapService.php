<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of SoapService
 *
 * @author Vojta
 */
abstract class SoapService extends \Nette\Object {

	/**
	 *
	 * @var \SoapClient
	 */
	protected $service;

	public function __construct(\SoapClient $service)
	{
		$this->service = $service;
	}

}
