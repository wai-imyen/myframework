<?php
namespace App\Model;

use Yen\Model;

class BaseModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	public function _init()
	{
		$this->table = '';
	}
}