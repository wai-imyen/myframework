<?php

namespace Yen;

use PDO;

class Sql
{
	protected $_conn;

	/**
	 * 連接資料庫
	 */
	public function connect($host, $username, $password, $dbname)
	{
		$this->_conn = new Pdodb($host, $username, $password, $dbname);

		if ( ! $this->_conn)
		{
			exit('資料庫無法連結');
		}
		else
		{
			$this->_conn->setDebugMode(FALSE);
			$this->_conn->setFetchMode(PDO::FETCH_ASSOC);
		}
	}
}
