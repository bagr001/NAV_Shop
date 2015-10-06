<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of ItemRepository
 *
 * @author Vojta
 */
class ItemRepository extends \Nette\Object {

	/**
	 *
	 * @var \Nette\Database\Context
	 */
	private $db;

	public function __construct(\Nette\Database\Context $db)
	{
		$this->db = $db;
	}

	public function getAll($activeOnly = true)
	{
		return $this->db->query('SELECT * FROM `items`')->fetchAll();
	}

}
