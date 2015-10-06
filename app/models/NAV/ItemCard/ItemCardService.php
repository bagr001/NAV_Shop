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

/**
 * Description of ItemCardService
 *
 * @author Vojta
 */
class ItemCardService extends NAV_Service {

	public function getAvalibleItems()
	{
		$filters = [
			new NFilter(ICF::INVENTORY_POSTING_GROUP, 'FINISHED'),
			new NFilter(ICF::UNIT_PRICE, '<>0'),
		];
		return $this->ReadMultiple(0, $filters);
	}

}
