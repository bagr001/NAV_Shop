<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls;

use \App\Controls\Forms\ISignFormControlFactory;
use \App\Controls\Forms\IRegisterFormControlFactory;

/**
 * Description of SignRegisterControl
 *
 * @author Vojta
 */
class SignRegisterControl extends \Nette\Application\UI\Control {

	/**
	 *
	 * @var ISignFormControlFactory
	 */
	private $sff;

	/**
	 *
	 * @var IRegisterFormControlFactory
	 */
	private $rff;

	/**
	 *
	 * @var array
	 */
	public $onSuccess;

	public function __construct(ISignFormControlFactory $sff, IRegisterFormControlFactory $rff)
	{
		parent::__construct();
		$this->sff = $sff;
		$this->rff = $rff;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/signRegister.latte');
		$this->template->render();
	}

	/**
	 * Sign-in form factory.
	 * @return Forms\SignFormControl
	 */
	protected function createComponentSignInForm()
	{
		$c = $this->sff->create();
		$c->onSuccess[] = function () {
			$this->onSuccess();
		};
		return $c;
	}

	/**
	 * Register form factory.
	 * @return Forms\RegisterFormControl
	 */
	protected function createComponentRegisterForm()
	{
		$c = $this->rff->create();
		$c->onSuccess[] = function () {
			$this->onSuccess();
		};
		return $c;
	}

}
