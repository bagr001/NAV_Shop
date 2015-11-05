<?php

namespace App\Presenters;

use Nette;
use \App\Model\ShoppingCart;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/**
	 *
	 * @var ShoppingCart
	 */
	protected $shoppingCart;

	public function __construct(ShoppingCart $shoppingCart)
	{
		parent::__construct();
		$this->shoppingCart = $shoppingCart;
	}

	public function beforeRender()
	{
		$this->template->shoppingCartItems = $this->shoppingCart->getItemsCount();
	}

	public function afterRender()
	{
		if ($this->ajax) {
			$this->redrawControl('flashMsg');
			$this->redrawControl('shoppingCartMenu');
		}
	}

}
