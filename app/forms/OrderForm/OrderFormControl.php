<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls\Forms;

use \Nette\Application\UI\Form;
use \Nette\Security\User;
use \App\Model\Basket;
use \App\Model\NAV\Customer\CustomerService;
use \App\Model\NAV\Customer\CustomerFields as CF;
use \App\Model\NAV\Countries\CountriesService;
use \App\Model\NAV\SalesOrder\SalesOrderService;
use \App\Model\NAV\SalesOrder\SalesOrder;
use \App\Model\NAV\SalesOrder\SalesOrderFields as SOF;
use \App\Model\NAV\SalesOrder\SalesOrderLine;
use \App\Model\NAV\SalesOrder\SalesOrderLineFields as SOLF;

/**
 * Description of OrderFormControl
 *
 * @author Vojta
 */
class OrderFormControl extends CustomerFormControl {

	/**
	 *
	 * @var SalesOrderService
	 */
	private $sos;

	/**
	 *
	 * @var Basket
	 */
	private $basket;

	public function __construct(User $user, CustomerService $cus, CountriesService $cs, Basket $basket, SalesOrderService $sos)
	{
		parent::__construct($user, $cus, $cs);
		$this->sos = $sos;
		$this->basket = $basket;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/order.latte');
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = parent::createComponentForm();

		$form->addCheckbox('agree', 'Agree with all terms and conditions.')
				->addRule(Form::EQUAL, 'You have to agree with our conditions', TRUE);



		$form['send']->caption = 'Place order';
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{

		unset($values->agree);
		$values[CF::GEN_BUS_POSTING_GROUP] = 'NATIONAL';
		$values[CF::VAT_BUS_POSTING_GROUP] = 'NATIONAL';
		$values[CF::CUSTOMER_POSTING_GROUP] = 'DOMESTIC';
		$values[CF::KEY] = $this->cus->Read($this->user->id)->{CF::KEY};
		$values[CF::NO] = $this->user->id;
		$this->cus->Update($values);

		$order =  new SalesOrder;
//		$order->Bill_to_Customer_No = $this->user->id;
		$order->Sell_to_Customer_No = $this->user->id;
//		$order->VAT_Bus_Posting_Group = 'NATIONAL';
//		$order->Currency_Code = 'EUR';

		$lines = [];
		foreach ($this->basket->getAll() as $item) {
			$line = new SalesOrderLine;
			$line->Type = 'Item';
			$line->No = $item[Basket::KEY_FIELD];
			$line->Quantity = $item[Basket::ORDER_QUANTITY_FIELD];
//			$line->Line_Discount_Percent = '0.0';
//			$line->Line_Discount_Amount = 0;
//			$line->Inv_Discount_Amount = 0;
			$lines[] = $line;
		}

		$order->SalesLines = $lines;

		$result = $this->sos->Create($order);

		if($result){
			$this->basket->clear();
			$this->onSuccess($values);

		} else {

		}

	}

}
