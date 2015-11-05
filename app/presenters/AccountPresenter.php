<?php

namespace App\Presenters;

use \App\Model\ShoppingCart;
use \App\Model\NAV\ItemCard\ItemCardFields as ICF;
use \App\Model\NAV\NAV_Filter as NFilter;
use \App\Model\NAV\ItemCard\ItemCardService;
use \App\Model\NAV\SalesOrder\SalesOrderFields AS SOF;
use \App\Model\NAV\SalesOrder\SalesOrderService;
use \App\Controls\Forms\ICustomerFormControlFactory;
use \App\Controls\Forms\IRatingFormControlFactory;

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

	/**
	 *
	 * @var IRatingFormControlFactory
	 */
	private $rff;

	/**
	 *
	 * @var ItemCardService
	 */
	private $ics;

	/**
	 *
	 * @var string
	 */
	private $item_no;

	public function __construct(ShoppingCart $shoppingCart, SalesOrderService $sos, ICustomerFormControlFactory $cff, IRatingFormControlFactory $rff, ItemCardService $ics)
	{
		parent::__construct($shoppingCart);
		$this->sos = $sos;
		$this->cff = $cff;
		$this->rff = $rff;
		$this->ics = $ics;
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
			$this->redirect('Account:orders');
		}
	}

	public function actionRatings()
	{
		$result = $this->ics->getEmptyRatings($this->user->id);
		if ($result) {
			$this->template->items = $this->ics->ReadMultiple(0, new NFilter(ICF::NO, array_map(function($row) {
						return $row->items_no;
					}, $result)));
		} else {
			$this->template->items = [];
		}
	}

	public function actionRating($no)
	{
		$item = $this->ics->Read($no);
		if ($item && $this->ics->isRatingOpen($this->user->id, $no)) {
			$this->item_no = $no;
			$this->template->item = $item;
		} else {
			$this->redirect('Account:ratings');
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

	protected function createComponentRatingForm()
	{
		$c = $this->rff->create($this->item_no);
		$c->onSuccess[] = function () {
			$this->redirect('Account:ratings');
		};
		return $c;
	}

}
