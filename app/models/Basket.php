<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

use \App\Model\NAV\NAV_Filter as NFilter;
use \App\Model\NAV\ItemCard\ItemCardFields as ICF;

/**
 * Description of Basket
 *
 * @author Vojta
 */
class Basket extends \Nette\Object {

	/** Session key item field  */
	const KEY_FIELD = ICF::NO;

	/** Name of order quantity field  */
	const ORDER_QUANTITY_FIELD = 'Basket_Quantity';

	/**
	 *
	 * @var \Nette\Http\SessionSection
	 */
	private $session;

	/**
	 *
	 * @var NAV\ItemCard\ItemCardService
	 */
	private $itemCardService;

	public function __construct(\Nette\Http\SessionSection $session, NAV\ItemCard\ItemCardService $itemCardService)
	{
		$this->session = $session;
		$this->itemCardService = $itemCardService;
	}

	/**
	 * Returns quantity by item key
	 * @param string $key
	 * @return int
	 */
	public function getQuantity($key)
	{
		return (int) $this->session[$key][self::ORDER_QUANTITY_FIELD];
	}

	/**
	 * Returns number of unique items
	 * @retur int
	 */
	public function getItemsCount()
	{
		return count($this->getKeys());
	}

	public function getAll($full = false)
	{
		if ($full) {
			$keys = $this->getKeys();
			if ($keys) {
				$filer = new NFilter(self::KEY_FIELD, $keys);
				$items = $this->itemCardService->ReadMultiple(0, $filer);
				return array_map(function($item) {
					$item->{self::ORDER_QUANTITY_FIELD} = $this->getQuantity($item->{self::KEY_FIELD});
					return $item;
				}, $items);
			}
			return [];
		} else {
			return $this->session;
		}
	}

	/**
	 * Adds item to the basket
	 * @param string $key Item key
	 * @param number $quantity
	 * @return void
	 */
	public function add($key, $quantity)
	{
		$this->session[$key] = $this->createItem($key, $quantity + $this->session[$key][self::ORDER_QUANTITY_FIELD]);
	}

	/**
	 * Updates item quantity
	 * @param string $key Item key
	 * @param number $quantity
	 * @return void
	 */
	public function update($key, $quantity)
	{
		$this->session[$key] = $this->createItem($key, $quantity >= 0 ? $quantity : 0);
	}

	/**
	 * Removes item or certain quantity from the basket
	 * @param string $key Item key
	 * @param number $quantity
	 * @return void
	 */
	public function remove($key, $quantity = 0)
	{
		$new_quantity = $this->session[$key][self::ORDER_QUANTITY_FIELD] - $quantity;
		if ($quantity && $new_quantity > 0) {
			$this->session[$key] = $this->createItem($key, $new_quantity);
		} else {
			unset($this->session[$key]);
		}
	}

	/**
	 * Removes all items from the basket
	 * @return void
	 */
	public function clear()
	{
		$this->session->remove();
	}

	/**
	 * Generates item record
	 * @param string $key Item key
	 * @param number $quantity
	 * @return array
	 */
	private function createItem($key, $quantity)
	{
		return [
			self::KEY_FIELD => $key,
			self::ORDER_QUANTITY_FIELD => $quantity
		];
	}

	/**
	 * Returns array of item keys
	 * @return array
	 */
	private function getKeys()
	{
		$out = [];
		foreach ($this->session as $key => $val) {
			$out[] = $key;
		}
		return $out;
	}

}
