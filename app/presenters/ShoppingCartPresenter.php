<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use \App\Model\ShoppingCart;
use \App\Model\CurrencyConvertor;
use \App\Controls\IShoppingCartControlFactory;
use \App\Controls\ISignRegisterControlFactory;
use \App\Controls\Forms\IOrderFormControlFactory;

/**
 * Description of ShoppingCartPresenter
 *
 * @author Vojta
 */
class ShoppingCartPresenter extends BasePresenter {

	/**
	 *
	 * @var IShoppingCartControlFactory
	 */
	private $shoppingCartControlFactory;

	/**
	 *
	 * @var CurrencyConvertor
	 */
	private $currencyConvertor;

	/**
	 *
	 * @var ISignRegisterControlFactory
	 */
	private $srff;

	/**
	 *
	 * @var IOrderFormControlFactory
	 */
	private $off;

	public function __construct(ShoppingCart $shoppingCart, IShoppingCartControlFactory $shoppingCartControlFactory, CurrencyConvertor $currencyConvertor, ISignRegisterControlFactory $srff, IOrderFormControlFactory $off)
	{
		parent::__construct($shoppingCart);
		$this->shoppingCartControlFactory = $shoppingCartControlFactory;
		$this->currencyConvertor = $currencyConvertor;
		$this->srff = $srff;
		$this->off = $off;
	}

	public function actionOrder()
	{
		if(!$this->user->loggedIn || !$this->shoppingCart->getItemsCount()){
			$this->redirect('Homepage:');
		}
	}

	public function actionSuccess()
	{
		if(!$this->user->loggedIn){
			$this->redirect('Homepage:');
		}
	}

	public function renderCart()
	{
		if (!$this->ajax || $this->isControlInvalid('shoppingCart')) {
			$this->template->items = $this->shoppingCart->getAll(true);
			$this->template->rateEurUsd = $this->currencyConvertor->getConversionRate(CurrencyConvertor::CURRENCY_CODE_EUR, CurrencyConvertor::CURRENCY_CODE_USD);
		}
	}

	protected function createComponentShoppingCart()
	{
		$c = $this->shoppingCartControlFactory->create();
		$c->onAdd[] = function($key) {
//			$this->flashMessage('Added 1x ' . $key);
			$this->redrawControl('shoppingCart');
		};
		$c->onRemove[] = function($key) {
//			$this->flashMessage('Removed 1x ' . $key);
			$this->redrawControl('shoppingCart');
		};
		return $c;
	}

	protected function createComponentSignRegisterForm()
	{
		$c = $this->srff->create();
		$c->onSuccess[] = function () {
			$this->redirect('ShoppingCart:order');
		};
		return $c;
	}

	protected function createComponentOrderForm()
	{
		$c = $this->off->create();
		$c->onSuccess[] = function () {
			$this->redirect('ShoppingCart:success');
		};
		return $c;
	}

}
