<?php

namespace App\Presenters;

use \App\Model\Basket;
use \App\Controls\ISignRegisterControlFactory;

class SignPresenter extends BasePresenter {

	/**
	 *
	 * @var ISignRegisterControlFactory
	 */
	private $srff;

	public function __construct(Basket $basket, ISignRegisterControlFactory $srff)
	{
		parent::__construct($basket);
		$this->srff = $srff;
	}

	protected function createComponentSignRegisterForm()
	{
		$c = $this->srff->create();
		$c->onSuccess[] = function () {
			$this->redirect('Homepage:');
		};
		return $c;
	}

	public function actionOut()
	{
		$this->user->logout(TRUE);
//		$this->flashMessage('You have been signed out.');
		$this->redirect('Homepage:');
	}

}
