<?php
/**
 *	SQL Log 存入資料庫
 *
 *	@param string $userQuery	// SQL 語法
 *	@param string $userId		// 使用者 id
 *	@param string $userType		// 使用者類型
 *
 *	@return VOID
 */
if ( ! function_exists('SetLog'))
{
	function SetLog($userQuery, $userId = '', $userType = '')
	{
		global $_conn;

		$record = array();
		$record["userQuery"] 	= $userQuery;
		$record["userId"]  		= ($userId) ? $userId : (isset($_SESSION['TW_MemberID'])) ? $_SESSION['TW_MemberID'] : 0;
		$record["userType"]  	= ($userType) ? $userType : (isset($_SESSION['TW_Level'])) ? $_SESSION['TW_Level'] : 'not';
		$record["ip"]  			= ip();
		$record["createTime"]  	= date('Y-m-d H:i:s');

		// $_conn->autoExecute('log', $record, 'INSERT');
	}
}

/**
 *	更新資料庫資料
 *
 *	@param string $table	// 資料庫 table
 *	@param string $columns	// 更新資料
 *	@param string $where	// SQL WHERE
 *
 *	@return res
 */
if ( ! function_exists('UpdateDB'))
{
	function UpdateDB($table, $columns, $where)
	{
		global $_conn;

		// 取得更新 SQL 語法
		$columns_sql = array();
		foreach ($columns as $key => $val)
		{
			$columns_sql[] = $key .' = :'.$key;
		}
		$sql_prepare = 'UPDATE '. $table . ' SET '. implode(',', $columns_sql) . ' WHERE ' . $where;
		$sql = $_conn->getSql($sql_prepare, $columns);

		// 更新資料
		$res = $_conn->autoExecute($table, $columns, 'UPDATE', $where);

		// 成功時，新增 Log 紀錄
		if ($res && $sql)
		{
			SetLog($sql);
		}

		return $res;
	}
}

/**
 *	新增資料庫資料
 *
 *	@param string $table	// 資料庫 table
 *	@param string $columns	// 新增資料
 *	@param string $id		// id 欄位名稱
 *
 *	@return insert_Id
 */
if ( ! function_exists('AddDB'))
{
	function AddDB($table, $columns, $id = NULL)
	{
		global $_conn;

		// 取得新增 SQL 語法
		$columns_sql = array();
		foreach ($columns as $key => $val)
		{
			$columns_sql[] = $key .' = :'.$key;
		}
		$sql_prepare = 'INSERT INTO '. $table . ' SET '. implode(',', $columns_sql);
		$sql = $_conn->getSql($sql_prepare, $columns);

		// 新增資料
		$insert_Id = $_conn->autoExecute($table, $columns, 'INSERT');

		// 成功時，新增 Log 紀錄
		if ($insert_Id)
		{
			SetLog($sql);
		}

		return $insert_Id;
	}
}

/**
 *	刪除資料庫資料
 *
 *	@param string $sql	// SQL 語法
 *
 *	@return res
 */
if ( ! function_exists('DeleteDB'))
{
	function DeleteDB($sql, $columns = array())
	{
		global $_conn;

		// 刪除資料
		$res = $_conn->Query($sql, $columns);

		// 成功時，新增 Log 紀錄
		if ($res)
		{
			SetLog($sql);
		}

		return $res;
	}
}
