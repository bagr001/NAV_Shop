<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controls\Forms;

/**
 *
 * @author Vojta
 */
interface IRatingFormControlFactory {

	/**
	 * @return RatingFormControl
	 */
	public function create($item_no);

}
