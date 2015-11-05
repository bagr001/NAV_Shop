<?php

namespace App\Presenters;

use \App\Model\ShoppingCart;
use \App\Controls\IShoppingCartControlFactory;
use \App\Model\NAV\ItemCard\ItemCardService;

class HomepagePresenter extends BasePresenter {

	/**
	 *
	 * @var IShoppingCartControlFactory
	 */
	private $shoppingCartControlFactory;

	/**
	 *
	 * @var ItemCardService
	 */
	private $ics;

	public function __construct(ShoppingCart $shoppingCart, IShoppingCartControlFactory $shoppingCartControlFactory, ItemCardService $ics)
	{
		parent::__construct($shoppingCart);
		$this->ics = $ics;
		$this->shoppingCartControlFactory = $shoppingCartControlFactory;
	}

	/**
	 *
	 * @return \App\Controls\ShoppingCartControl
	 */
	protected function createComponentShoppingCart()
	{
		$c = $this->shoppingCartControlFactory->create();
//		$c->onAdd[] = function($key){
//			$this->flashMessage('Added 1x ' . $key);
//			$this->redrawControl('shoppingCart');
//		};
//		$c->onRemove[] = function($key){
//			$this->flashMessage('Removed 1x ' . $key);
//			$this->redrawControl('shoppingCart');
//		};
		return $c;
	}

	public function renderDefault()
	{
		if (!$this->ajax) {
			$this->template->items = $this->ics->getAvalibleItems();
		}
	}

	public function renderDetail($no)
	{
		$item = $this->ics->Read($no);
		if ($item) {
			$this->template->item = $item;
		} else {
			$this->redirect('Homepage:default');
		}
	}

}
