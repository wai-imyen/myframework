<?php
namespace Yen;

class Model extends Sql
{
	protected $_table;
	protected static $_dbConfig = array();

	public function __construct()
	{
		// 連接資料庫
		$this->connect(self::$_dbConfig['host'], self::$_dbConfig['username'], self::$_dbConfig['password'], self::$_dbConfig['dbname']);
	}

	public static function setDbConfig($config)
	{
		self::$_dbConfig = $config;
	}
}
