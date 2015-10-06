<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\NAV;

use \Tracy\Debugger;

/**
 * Description of NAV_Service
 *
 * @author Vojta
 */
class NAV_Service extends \App\Model\SoapService {

	protected $objectType;

	public function __construct(\SoapClient $service, $objectType)
	{
		parent::__construct($service);
		$this->objectType = $objectType;
	}

	public function Read($No)
	{
		try {
			$result = $this->service->Read(['No' => $No]);
			return $result ? $result->{$this->objectType} : $result;
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function ReadByRecId($recId)
	{
		try {
			return $this->service->ReadByRecId(['recId' => $recId]);
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function ReadMultiple($setSize = 0, $filter = [], $bookmarkKey = '')
	{
		try {
			$result = $this->service->ReadMultiple(['setSize' => $setSize, 'filter' => $filter, 'bookmarkKey' => $bookmarkKey]);
			if ($result && isset($result->ReadMultiple_Result->{$this->objectType})) {
				$out = $result->ReadMultiple_Result->{$this->objectType};
				return is_array($out) ? $out : [$out];
			}
			return [];
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function Create($data)
	{
		try {
			$result = $this->service->Create([$this->objectType => $data]);
			return $result->{$this->objectType};
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function Update($data)
	{
		try {
			$result = $this->service->Update([$this->objectType => $data]);
			return $result->{$this->objectType};
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function IsUpdated($Key)
	{
		try {
			$result = $this->service->IsUpdated(['Key' => $Key]);
			return $result ? $result->IsUpdated_Result ? true : false : false;
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

	public function Delete($Key)
	{
		try {
			return $this->service->Delete(['Key' => $Key]);
		} catch (\SoapFault $e) {
			Debugger::log($e);
			return null;
		}
	}

}
