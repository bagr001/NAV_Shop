<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\NAV;

/**
 * Description of NAV_Filter
 *
 * @author Vojta
 */
class NAV_Filter {

	public $Field;
	public $Criteria;

	public function __construct($Field = '', $Criteria = '')
	{
		$this->Field = $Field;
		$this->Criteria = is_array($Criteria) ? implode('|', $Criteria) : $Criteria;
	}

}
