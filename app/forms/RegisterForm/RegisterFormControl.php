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
 * Description of RegisterFormControl
 *
 * @author Vojta
 */
class RegisterFormControl extends \Nette\Application\UI\Control {

	/**
	 *
	 * @var User
	 */
	private $user;

	/**
	 *
	 * @var \App\Model\UserManager
	 */
	private $um;

	/**
	 *
	 * @var array
	 */
	public $onSuccess;

	public function __construct(User $user, \App\Model\UserManager $um)
	{
		parent::__construct();
		$this->user = $user;
		$this->um = $um;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/register.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = new Form;

		$form->addText('name', 'Name:')
				->setRequired('Please enter your name.');

		$form->addText('email', 'Email:')
				->setRequired('Please enter your email.')
				->addRule(Form::EMAIL, 'Please enter valid email address.');

		$form->addPassword('password', 'Password:')
				->setRequired('Please enter your password.');

		$form->addPassword('password2', 'Password again:')
				->setRequired('Please enter your password again.')
				->addRule(Form::EQUAL, 'Passwords are not equal.', $form['password']);

		$form->addCheckbox('agree', 'Agree with conditions')
				->addRule(Form::EQUAL, 'You have to agree with our conditions', TRUE);

		$form->addSubmit('send', 'Register');

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{
		try {
			$user = $this->um->add($values->email, $values->password, $values->name);
			if ($user) {
				$this->user->login($values->email, $values->password);
				$this->onSuccess();
			} else {
				$form->addError('Oops! Your account could not be created for some reason :-(');
				$this->redrawControl('form');
			}
		} catch (\App\Model\DuplicateEmailException $e) {
			$form->addError('Sorry, this email address is already registered. Sign in or use another one.');
			$this->redrawControl('form');
		}
	}

}
