<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\NAV\Countries;

/**
 * Description of CountriesService
 *
 * @author Vojta
 */
class CountriesService extends \App\Model\NAV\NAV_Service {

	public function getPairs()
	{
		$countries = $this->ReadMultiple();
		$out = [];
		foreach ($countries as $country) {
			$out[$country->{CountriesFields::CODE}] = $country->{CountriesFields::NAME};
		}
		return $out;
	}

}
