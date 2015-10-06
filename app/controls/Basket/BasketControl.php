<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls;

use \App\Model\Basket;

/**
 * Description of BasketControl
 *
 * @author Vojta
 */
class BasketControl extends \Nette\Application\UI\Control {

	/** @var Basket */
	private $basket;

	/** @var array */
	public $onAdd, $onRemove;

	public function __construct(Basket $basket)
	{
		parent::__construct();
		$this->basket = $basket;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/basket.latte');
		$this->template->render();
	}

	public function renderBuy($key)
	{
		$this->template->setFile(__DIR__ . '/basketBuy.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function renderAddOne($key)
	{
		$this->template->setFile(__DIR__ . '/basketAddOne.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleAddOne($key)
	{
		$this->basket->add($key, 1);
		$this->onAdd($key);
	}

	public function renderRemoveOne($key)
	{
		$this->template->setFile(__DIR__ . '/basketRemoveOne.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleRemoveOne($key)
	{
		$this->basket->remove($key, 1);
		$this->onRemove($key);
	}

	public function renderRemoveAll($key)
	{
		$this->template->setFile(__DIR__ . '/basketRemoveAll.latte');
		$this->template->key = $key;
		$this->template->render();
	}

	public function handleRemoveAll($key)
	{
		$this->basket->remove($key);
		$this->onRemove($key);
	}

}
