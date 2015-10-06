<?php

namespace App\Presenters;

use Nette;
use \App\Model\Basket;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/**
	 *
	 * @var Basket
	 */
	protected $basket;

	public function __construct(Basket $basket)
	{
		parent::__construct();
		$this->basket = $basket;
	}

	public function beforeRender()
	{
		$this->template->shoppingCartItems = $this->basket->getItemsCount();
	}

	public function afterRender()
	{
		if ($this->ajax) {
			$this->redrawControl('flashMsg');
			$this->redrawControl('shoppingCart');
		}
	}

}
