<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of ArrayMerger
 *
 * @author Vojta
 */
class ArrayMerger {

//	public static function mergeByKeyVal($aa, $ab, $kb)
//	{
//		foreach ((array) $aa as $va => $ka) {
////			$a[]
//			if(isset($ab[$a[$ka]]))
//		}
//	}

	public static function mergeByValues($aa, $ka, $ab, $kb = null, $asObject = false)
	{
		$kb = $kb ? $kb : $ka;

		$out = (array) $aa;

		foreach ((array) $aa as $_ka => $va) {
			$_va_ = (array) $va;
			foreach ((array) $ab as $b) {
				$_b_ = (array) $b;
				if ($_va_[$ka] == $_b_[$kb]) {
					$merge = array_merge($_va_, $_b_);
					$out[$_ka] = $asObject ? (object) $merge : $merge;
					break;
				}
			}
		}

		return $out;
	}

}
