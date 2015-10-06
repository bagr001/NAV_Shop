<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls\Forms;

use Nette\Application\UI\Form;
use \Nette\Security\User;
use \App\Model\NAV\Countries\CountriesService;
use \App\Model\NAV\Customer\CustomerService;
use \App\Model\NAV\Customer\CustomerFields as CF;

/**
 * Description of CustomerFormControl
 *
 * @author Vojta
 */
class CustomerFormControl extends \Nette\Application\UI\Control {

	/**
	 *
	 * @var User
	 */
	protected $user;

	/**
	 *
	 * @var CountriesService
	 */
	protected $cs;

	/**
	 *
	 * @var CustomerService
	 */
	protected $cus;

	/**
	 *
	 * @var array
	 */
	public $onSuccess;

	public function __construct(User $user, CustomerService $cus, CountriesService $cs)
	{
		parent::__construct();
		$this->user = $user;
		$this->cus = $cus;
		$this->cs = $cs;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/customer.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = new Form;

		$form->addText(CF::NAME, 'Name:')
				->setRequired('Please enter your name.');

		$form->addText(CF::E_MAIL, 'Email:')
				->setRequired('Please enter your email.')
				->addRule(Form::EMAIL, 'Please enter valid email address.');

		$form->addText(CF::PHONE_NO, 'Phone no.:')
				->setRequired('Please enter your phone number.');

		$form->addText(CF::ADDRESS, 'Street:')
				->setRequired('Please enter your street.');

		$form->addText(CF::CITY, 'Ciy:')
				->setRequired('Please enter your city.');

		$form->addText(CF::POST_CODE, 'Postal code:')
				->setRequired('Please enter your postal code.');

		$form->addSelect(CF::COUNTRY_REGION_CODE, 'Country:', $this->cs->getPairs())
				->setRequired('Please select your country.')
				->setValue('FI');

		$form->addSubmit('send', 'Save');

		$form->setValues((array)$this->cus->Read($this->user->id));

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{
		$values[CF::KEY] = $this->cus->Read($this->user->id)->{CF::KEY};
		$values[CF::NO] = $this->user->id;
		$this->cus->Update($values);
		$this->onSuccess($values);
	}

}
