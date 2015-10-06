<?php

namespace App\Presenters;

use \App\Model\Basket;
use \App\Model\NAV\NAV_Filter as NFilter;
use \App\Model\NAV\SalesOrder\SalesOrderFields AS SOF;
use \App\Model\NAV\SalesOrder\SalesOrderService;
use \App\Controls\Forms\ICustomerFormControlFactory;

class AccountPresenter extends BasePresenter {

	/**
	 *
	 * @var SalesOrderService
	 */
	private $sos;

	/**
	 *
	 * @var ICustomerFormControlFactory
	 */
	private $cff;

	public function __construct(Basket $basket, SalesOrderService $sos, ICustomerFormControlFactory $cff)
	{
		parent::__construct($basket);
		$this->sos = $sos;
		$this->cff = $cff;
	}

	protected function startup()
	{
		parent::startup();

		if (!$this->user->loggedIn) {
			$this->redirect('Homepage:');
		}
	}

	public function renderOrders()
	{
		$this->template->orders = $this->sos->ReadMultiple(0, new NFilter(SOF::SELL_TO_CUSTOMER_NO, $this->user->id));
	}

	public function renderOrderDetail($no)
	{
		$filters = [];
		$filters[] = new NFilter(SOF::SELL_TO_CUSTOMER_NO, $this->user->id); // security !!!
		$filters[] = new NFilter(SOF::NO, $no);

		$order = $this->sos->ReadMultiple(0, $filters);

		if ($order && count($order) == 1) {
			$this->template->order = $order[0];
		} else {
			throw new \Nette\Application\BadRequestException('This order number does not exist.');
		}
	}

	protected function createComponentCustomerForm()
	{
		$c = $this->cff->create();
		$c->onSuccess[] = function () {
			$this->redirect('this');
		};
		return $c;
	}

}
