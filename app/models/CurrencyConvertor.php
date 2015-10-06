<?php

namespace App\Model;

/**
 * Description of CurrencyConvertor
 *
 * @author Vojtech Lank
 */
class CurrencyConvertor extends \Nette\Object {

	const CURRENCY_CODE_EUR = "EUR";
	const CURRENCY_CODE_USD = "USD";
	const CURRENCY_CONVERTOR_SOAP_LOCATION = "http://www.webservicex.net/CurrencyConvertor.asmx?WSDL";
	const CURRENCY_CONVERTOR_PARAM_FROM_CURRENCY = "FromCurrency";
	const CURRENCY_CONVERTOR_PARAM_TO_CURRENCY = "ToCurrency";

	/**
	 * Converts value between given currencies
	 *
	 * @param double $value Value to convert
	 * @param string $from Current currency code
	 * @param string $to Desired currency code
	 *
	 * @return double Converted value
	 * @throws \Exception
	 */
	public function convert($value, $from, $to)
	{
		return $value * $this->getConversionRate($from, $to);
	}

	/**
	 * Return conversion rate between given currencies
	 *
	 * @param string $from Current currency code
	 * @param string $to Desired currency code
	 *
	 * @return double Converted value
	 * @throws \Exception
	 */
	public function getConversionRate($from, $to)
	{

		$data = array(
			self::CURRENCY_CONVERTOR_PARAM_FROM_CURRENCY => $from,
			self::CURRENCY_CONVERTOR_PARAM_TO_CURRENCY => $to
		);

		try {
			$client = new \SoapClient(self::CURRENCY_CONVERTOR_SOAP_LOCATION);
			return $client->ConversionRate($data)->ConversionRateResult;
		} catch (\SoapFault $e) {
			throw new \Exception('Error retreiving data from the remote server.');
		}
	}

}
