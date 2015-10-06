<?php

namespace App\Presenters;

use \App\Model\Basket;
use \App\Controls\IBasketControlFactory;
use \App\Model\NAV\ItemCard\ItemCardService;

class HomepagePresenter extends BasePresenter {

	/**
	 *
	 * @var IBasketControlFactory
	 */
	private $basketControlFactory;

	/**
	 *
	 * @var ItemCardService
	 */
	private $ics;

	public function __construct(Basket $basket, IBasketControlFactory $basketControlFactory, ItemCardService $ics)
	{
		parent::__construct($basket);
		$this->ics = $ics;
		$this->basketControlFactory = $basketControlFactory;
	}

	/**
	 *
	 * @return \App\Controls\BasketControl
	 */
	protected function createComponentBasket()
	{
		$c = $this->basketControlFactory->create();
//		$c->onAdd[] = function($key){
//			$this->flashMessage('Added 1x ' . $key);
//			$this->redrawControl('basket');
//		};
//		$c->onRemove[] = function($key){
//			$this->flashMessage('Removed 1x ' . $key);
//			$this->redrawControl('basket');
//		};
		return $c;
	}

	public function renderDefault()
	{
		if (!$this->ajax) {
			$this->template->items = $this->ics->getAvalibleItems();
		}
	}

}
