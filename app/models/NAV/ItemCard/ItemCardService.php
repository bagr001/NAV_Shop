<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\NAV\ItemCard;

use \App\Model\NAV\NAV_Service;
use \App\Model\NAV\NAV_Filter as NFilter;
use \App\Model\NAV\ItemCard\ItemCardFields as ICF;
use \App\Model\ItemRepository;

/**
 * Description of ItemCardService
 *
 * @author Vojta
 */
class ItemCardService extends NAV_Service {

	/**
	 * @var ItemRepository
	 */
	private $ir;

	public function __construct(\SoapClient $service, $objectType, ItemRepository $ir)
	{
		parent::__construct($service, $objectType);
		$this->ir = $ir;
	}

	public function getAvalibleItems()
	{
		$filters = [
			new NFilter(ICF::INVENTORY_POSTING_GROUP, 'FINISHED'),
			new NFilter(ICF::UNIT_PRICE, '<>0'),
		];

		$result = $this->ReadMultiple(0, $filters);
		$items = $this->ir->getAll();

		return \App\Model\ArrayMerger::mergeByValues($result, ICF::NO, $items, 'no', true);
	}

	public function Read($No)
	{
		$result = parent::Read($No);
		$item = $this->ir->getSingle($No);

		if ($result) {
			if ($item) {
				return \App\Model\ArrayMerger::mergeByValues([$result], ICF::NO, [$item], 'no', true)[0];
			} else {
				return $result;
			}
		} else {
			return null;
		}
	}

}
