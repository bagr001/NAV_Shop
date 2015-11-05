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

	/**
	 *
	 * @var \Nette\Database\Context
	 */
	private $db;

	public function __construct(\SoapClient $service, $objectType, \Nette\Database\Context $db)
	{
		parent::__construct($service, $objectType);
		$this->db = $db;
	}

	public function getAvalibleItems()
	{
		$filters = [
			new NFilter(ICF::INVENTORY_POSTING_GROUP, 'FINISHED'),
			new NFilter(ICF::UNIT_PRICE, '<>0'),
		];

		$result = $this->ReadMultiple(0, $filters);
		$items = $this->getAll();

		return \App\Model\ArrayMerger::mergeByValues($result, ICF::NO, $items, 'no', true);
	}

	public function Read($No)
	{
		$result = parent::Read($No);
		$item = $this->getSingle($No);

		if ($result) {
			$result->ratings = $this->getItemRatings($No);
			if ($item) {
				return \App\Model\ArrayMerger::mergeByValues([$result], ICF::NO, [$item], 'no', true)[0];
			} else {
				return $result;
			}
		} else {
			return null;
		}
	}

	public function getAll($activeOnly = true)
	{
		return $this->db->query('SELECT * FROM `items`')->fetchAll();
	}

	public function getSingle($no)
	{
		return $this->db->query('SELECT * FROM `items` WHERE `no` = ?', $no)->fetch();
	}

	public function addEmptyRating($customers_no, $items_no)
	{
		$this->db->query('INSERT INTO `items_ratings`', ['items_no' => $items_no, 'customers_no' => $customers_no]);
	}

	public function updateRating($customers_no, $items_no, $data)
	{
		$this->db->query('UPDATE `items_ratings` SET ? WHERE `customers_no` = ? AND `items_no` = ? AND `open` = 1', $data, $customers_no, $items_no);
	}

	public function getItemRatings($no)
	{
		return $this->db->query('SELECT * FROM `items_ratings` WHERE `items_no` = ? AND `open` = 0', $no)->fetchAll();
	}

	public function getEmptyRatings($customers_no)
	{
		return $this->db->query('SELECT * FROM `items_ratings` WHERE `customers_no` = ? AND `open` = 1', $customers_no)->fetchAll();
	}

	public function ratingExists($customers_no, $items_no)
	{
		return (bool) $this->db->query('SELECT COUNT(*) AS `cnt` FROM `items_ratings` WHERE `customers_no` = ? AND `items_no` = ?', $customers_no, $items_no)->fetch()->cnt;
	}

	public function isRatingOpen($customers_no, $items_no)
	{
		return (bool) $this->db->query('SELECT COUNT(*) AS `cnt` FROM `items_ratings` WHERE `customers_no` = ? AND `items_no` = ? AND `open` = 1', $customers_no, $items_no)->fetch()->cnt;
	}

}
