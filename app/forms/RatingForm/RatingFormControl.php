<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls\Forms;

use \Nette\Security\User;
use \Nette\Application\UI\Form;
use \App\Model\NAV\ItemCard\ItemCardService;

/**
 * Description of RatingFormControl
 *
 * @author Vojta
 */
class RatingFormControl extends \Nette\Application\UI\Control {

	/**
	 *
	 * @var User
	 */
	private $user;

	/**
	 *
	 * @var ItemCardService
	 */
	private $ics;

	/**
	 *
	 * @var array
	 */
	public $onSuccess;

	/**
	 *
	 * @var string
	 */
	private $item_no;

	public function __construct(User $user, ItemCardService $ics, $item_no)
	{
		parent::__construct();
		$this->user = $user;
		$this->ics = $ics;
		$this->item_no = $item_no;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/rating.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name:', null, 45)
				->setRequired('Please fill your name or nick name.')
				->setValue('Anonymous');

		$form->addTextArea('pros', 'Pros:', null, 5);

		$form->addTextArea('cons', 'Cons:', null, 5);

		$form['pros']->addConditionOn($form['cons'], Form::BLANK, TRUE)
				->setRequired('Please fill at least Pros or Cons.');

		$form['cons']->addConditionOn($form['pros'], Form::BLANK, TRUE)
				->setRequired('Please fill at least Pros or Cons.');

		$form->addRadioList('rating', 'Rating:', [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5])
				->setRequired('Please select your rating');

		$form->addSubmit('send', 'Save Rating');

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{
		$this->ics->updateRating($this->user->id, $this->item_no, $values);
		$this->onSuccess($values);
	}

}
