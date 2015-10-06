<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls\Forms;

use Nette\Application\UI\Form;
use Nette\Security\User;

/**
 * Description of SignFormControl
 *
 * @author Vojta
 */
class SignFormControl extends \Nette\Application\UI\Control {

	/**
	 *
	 * @var User
	 */
	private $user;

	/**
	 *
	 * @var array
	 */
	public $onSuccess;

	public function __construct(User $user)
	{
		parent::__construct();
		$this->user = $user;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/sign.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = new Form;

		$form->addText('email', 'Email:')
				->setRequired('Please enter your email.')
				->addRule(Form::EMAIL, 'Please enter valid email address.');

		$form->addPassword('password', 'Password:')
				->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->user->login($values->email, $values->password);
			$this->onSuccess();
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->redrawControl('form');
			$form->addError($e->getMessage());
		}
	}

}
