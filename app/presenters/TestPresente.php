<?php

namespace App\Presenters;


class TestPresenter extends BasePresenter
{

	/**
	 *
	 * @var \App\Model\NAV\SalesOrder\SalesOrderService
	 * @inject
	 */
	public $cs;

	/**
	 *
	 * @var \App\Model\UserManager
	 * @inject
	 */
	public $um;

	/**
	 *
	 * @var \App\Controls\Forms\IOrderFormControlFactory
	 * @inject
	 */
	public $cff;

	protected function createComponentCustomerForm()
	{
		$c = $this->cff->create();
		$c->onSuccess[] = function ($values) {
			print "<pre>";
			print_r($values);
			print "</pre>";
		};
		return $c;
	}

	public function renderL()
	{
//		$c = $this->um->add('vojta@lankovi.cz', 'a', 'Vojtěch Lank');
		$this->user->login('vojta@lankovi.cz', 'a');
		print "<pre>";
//		print_r($c);
		print "</pre>";
	}

	public function renderX()
	{
		$cs = $this->cs;

		$data = $cs->ReadMultiple();
//		$data = $cs->IsUpdated('24;EgAAAAJ7/0MAMAAwADAAMQAw6;2025360;'); // vojta
//		$data = $cs->IsUpdated('24;EgAAAAJ7/0MAMAAwADAAMQAw6;2025360;'); // vojta
//		$data = $cs->IsUpdated('28;EgAAAAJ7/0MAMAAwADAAMQAwAEE=6;2110770;'); // vojta A
//		$data = $cs->IsUpdated('28;EgAAAAJ7/0MAMAAwADAAMQAwAEE=6;2112wd010s;'); // vojta A updated
//		$data = $cs->Read('');

//		$data = $cs->IsUpdated('32;EgAAAAJ7CDAAMQAxADIAMQAyADEAMg==6;1294490;');

		print "<pre>";
//		print_r($data);
		var_dump($data);
		print "</pre>";
		$this->terminate();
	}

	public function renderDefault()
	{

//            $service_url = 'http://RIL-SQL.rilling.local:7047/DynamicsNAV71/WS/Yritys4_Cronus/Page/Customer';
            //http://ril-sql.rilling.local:7047/DynamicsNAV71/WS/Yritys4_Cronus/Page/Integration_Item_Card
//            $opt = array(
//                'login' => "Navuser1",
//                'password' => "Password1!",
//                'proxy_login' => "Navuser1",
//                'proxy_password' => "Password1!",
//                "wdsl_local_copy" => true
//            );


//            $c = new \NAV_SoapClient($service_url, $opt);
            //print_r($c->__getFunctions());
            //print_r($c->__getTypes());


//            $p = new \StdClass();
            //$p->filter = new \StdClass();
            //$p->filter->Field = '';
            //$p->filter->Criteria = '';
//            $p->setSize = 0;
//
//            $p->filter = new \Customer_Filter('Name', 'Vojta 2');
//
//            print_r($c->ReadMultiple($p));
//
//            $p2 = new \StdClass();
//            $cu = new \Customer();
//            $cu->Name = 'Vojta 2';
//            $p2->Customer = $cu;
//
            //print_r($c->Create($p2));
//           $wdsl = 'http://localhost:7047/DynamicsNAV70/WS/CRONUS%20International%20Ltd/Page/ItemCard';
//
//			$ch = curl_init($wdsl);
//			curl_setopt($ch, CURLOPT_HEADER, TRUE);
//			$credit = ($options['login'] . ':' . $options['password']);
//			curl_setopt($ch, CURLOPT_URL, $wdsl);
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//			curl_setopt($ch, CURLOPT_POST, true);
//			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
//			curl_setopt($ch, CURLOPT_USERPWD, $credit);
//			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
//			curl_setopt($ch, CURLOPT_FILE, $fp);
//			$x = curl_exec($ch);
//			curl_close($ch);
//			echo $x;

	}

}
