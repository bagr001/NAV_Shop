<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use \App\Model\Basket;
use \App\Model\CurrencyConvertor;
use \App\Controls\IBasketControlFactory;
use \App\Controls\ISignRegisterControlFactory;
use \App\Controls\Forms\IOrderFormControlFactory;

/**
 * Description of BasketPresenter
 *
 * @author Vojta
 */
class BasketPresenter extends BasePresenter {

	/**
	 *
	 * @var IBasketControlFactory
	 */
	private $basketControlFactory;

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

	public function __construct(Basket $basket, IBasketControlFactory $basketControlFactory, CurrencyConvertor $currencyConvertor, ISignRegisterControlFactory $srff, IOrderFormControlFactory $off)
	{
		parent::__construct($basket);
		$this->basketControlFactory = $basketControlFactory;
		$this->currencyConvertor = $currencyConvertor;
		$this->srff = $srff;
		$this->off = $off;
	}

	public function actionOrder()
	{
		if(!$this->user->loggedIn || !$this->basket->getItemsCount()){
			$this->redirect('Homepage:');
		}
	}

	public function actionSuccess()
	{
		if(!$this->user->loggedIn){
			$this->redirect('Homepage:');
		}
	}

	public function renderDefault()
	{
		if (!$this->ajax || $this->isControlInvalid('basket')) {
			$this->template->items = $this->basket->getAll(true);
			$this->template->rateEurUsd = $this->currencyConvertor->getConversionRate(CurrencyConvertor::CURRENCY_CODE_EUR, CurrencyConvertor::CURRENCY_CODE_USD);
		}
	}

	protected function createComponentBasket()
	{
		$c = $this->basketControlFactory->create();
		$c->onAdd[] = function($key) {
//			$this->flashMessage('Added 1x ' . $key);
			$this->redrawControl('basket');
		};
		$c->onRemove[] = function($key) {
//			$this->flashMessage('Removed 1x ' . $key);
			$this->redrawControl('basket');
		};
		return $c;
	}

	protected function createComponentSignRegisterForm()
	{
		$c = $this->srff->create();
		$c->onSuccess[] = function () {
			$this->redirect('Basket:order');
		};
		return $c;
	}

	protected function createComponentOrderForm()
	{
		$c = $this->off->create();
		$c->onSuccess[] = function () {
			$this->redirect('Basket:success');
		};
		return $c;
	}

}
