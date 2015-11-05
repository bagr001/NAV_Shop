<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls;

use \App\Model\ShoppingCart;

/**
 * Description of ShoppingCartControl
 *
 * @author Vojta
 */
class ShoppingCartControl extends \Nette\Application\UI\Control {

	/** @var ShoppingCart */
	private $shoppingCart;

	/** @var array */
	public $onAdd, $onRemove;

	public function __construct(ShoppingCart $shoppingCart)
	{
		parent::__construct();
		$this->shoppingCart = $shoppingCart;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/shoppingCart.latte');
		$this->template->render();
	}

	public function renderBuy($key)
	{
		$this->template->setFile(__DIR__ . '/shoppingCartBuy.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function renderAddOne($key)
	{
		$this->template->setFile(__DIR__ . '/shoppingCartAddOne.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleAddOne($key)
	{
		$this->shoppingCart->add($key, 1);
		$this->onAdd($key);
	}

	public function renderRemoveOne($key)
	{
		$this->template->setFile(__DIR__ . '/shoppingCartRemoveOne.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleRemoveOne($key)
	{
		$this->shoppingCart->remove($key, 1);
		$this->onRemove($key);
	}

	public function renderRemoveAll($key)
	{
		$this->template->setFile(__DIR__ . '/shoppingCartRemoveAll.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleRemoveAll($key)
	{
		$this->shoppingCart->remove($key);
		$this->onRemove($key);
	}

}
