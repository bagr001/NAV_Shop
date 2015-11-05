<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\NAV;

/**
 * Description of NAV_SoapClient
 *
 * @author Vojta
 */
class NAV_SoapClient extends \SoapClient {

	/**
	 * Since the PHP SOAP package does not support basic authentication
	 * this class downloads the WDSL file using the cURL package and
	 * creates a local copy of the wdsl on your server.
	 * Make sure you provide the following additional parameter in the
	 * $options Array:
	 * wdsl_local_copy => true
	 */
	private $login;
	private $password;
	private $cache_dir; // = TEMP_DIR;
	private $cache_url; // = 'http://localhost:88/NAV_WS/www/temp/';

	public function __construct($wdsl, $options)
	{
		$this->login = $options['login'];
		$this->password = $options['password'];

		if (
				isset($options['wdsl_local_copy']) && $options['wdsl_local_copy'] &&
				isset($options['cache_dir']) && isset($options['cache_url']) &&
				isset($options['login']) && isset($options['password'])
		) {

			$this->cache_dir = $options['cache_dir'] . '/\/';
			$this->cache_url = $options['cache_url'];

			unset($options['wdsl_local_copy'], $options['cache_dir'], $options['cache_url']);

			$file = md5(uniqid()) . '.xml';

			if (($fp = fopen($this->cache_dir . $file, "w")) == false) {
				throw new \Exception('Could not create local WDSL file (' . $this->cache_dir . $file . ')');
			}

			$ch = curl_init();
			$credit = ($options['login'] . ':' . $options['password']);
			curl_setopt($ch, CURLOPT_URL, $wdsl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_USERPWD, $credit);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_FILE, $fp);

			if (($xml = curl_exec($ch)) === false) {
				//curl_close($ch);
				fclose($fp);
				unlink($this->cache_dir . $file);

				throw new Exception(curl_error($ch));
			}

			curl_close($ch);
			fclose($fp);
			$wdsl = $this->cache_url . $file;
		}

		parent::__construct($wdsl, $options);

		unlink($this->cache_dir . $file);
	}

	public function __doRequest($request, $location, $action, $version, $one_way = 0)
	{

		$headers = array(
			'Method: POST',
			'Connection: Keep-Alive',
			'User-Agent: PHP-SOAP-CURL',
			'Content-Type: text/xml; charset=utf-8',
			'SOAPAction: "' . $action . '"',
		);

		$ch = curl_init($location);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->password);

		$response = curl_exec($ch);

		if ($response === false) {
			throw new SoapFault('CURL error: ' . curl_error($handle), curl_errno($handle));
		}

		return $response;
	}

}
