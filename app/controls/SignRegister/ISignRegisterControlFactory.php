<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls;

/**
 *
 * @author Vojta
 */
interface ISignRegisterControlFactory {

	/**
	 * @return SignRegisterControl
	 */
	public function create();

}
