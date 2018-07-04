<?php
namespace Yen;

use PDO;
use PDOException;

class Pdodb
{
    public $db_type = 'mysql';				// 資料庫類型
	public $conn = NULL;					// 資料庫連線
	public $debug = FALSE;					// 除錯模式
    public $fetch_mode = PDO::FETCH_ASSOC;	// 陣列引索模式

	/**
	 * 構造函數
	 *
	 * @param string $host		// Host
	 * @param string $username	// 使用者名稱
	 * @param string $password	// 使用者密碼
	 * @param string $dbname	// 資料庫名稱
	 */
	public function __construct($host, $username, $password, $dbname)
	{
		$this->connect($host, $username, $password, $dbname);
		$this->setDebugMode($this->debug);

		// PDO 禁用模擬預處理語句，並使用 real parepared statements
		$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

		$this->conn->exec('SET NAMES utf8;');
		$this->conn->exec('SET CHARACTER_SET_CLIENT=utf8;');
		$this->conn->exec('SET CHARACTER_SET_RESULTS=utf8;');
	}

	/**
	 * 析構函數
	 */
    public function __destruct()
	{
       $this->close();
    }

	/**
	 * 連結資料庫
	 *
	 * @param string $host		// Host
	 * @param string $username	// 使用者名稱
	 * @param string $password	// 使用者密碼
	 * @param string $dbname	// 資料庫名稱
	 */
    public function connect($host, $username, $password, $dbname)
	{
        try
		{
            $this->conn = new PDO(
				$this->db_type . ':host=' . $host . ';dbname=' . $dbname . ';charset=utf8',
				$username,
				$password
			);
        }
        catch (PDOException $e)
		{
            exit("資料庫連結失敗：" . $e->getMessage());
        }
    }

	/**
	 * 關閉資料連接
	 */
    public function close()
	{
        $this->conn = null;
    }

	/**
	 * 設定除錯模式
	 *
	 * @param boolen $mode	// 除錯模式
	 */
	public function setDebugMode($mode)
	{
		$this->debug = $mode;

		if ($this->debug)
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}
	}

	/**
	 * 設定陣列引索模式
	 *
	 * @param boolen $mode	// 模式 ASSOC 關聯，NUM  數字，BOTH 關聯、數字都有， OBJ 物件
	 */
	public function setFetchMode($mode)
	{
		$this->fetch_mode = $mode;
	}

	/**
	 * 內部取得資料
	 *
	 * @param string $type		// 取得模式，1: fetch，2: fetchAll, 3: rowCount, 4: $stmt
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 */
	private function _fetch($type, $sql, $columns = array())
	{
		$result = array();

		$stmt = $this->conn->prepare($sql);

		if ($columns)
		{
			if (is_array($columns))
			{

				foreach ($columns as $key => &$val)
				{
					$parameter = (is_numeric($key)) ? ($key + 1) : ':'.$key;

					if (is_array($val))
					{
						if (isset($val[1]) && isset($val[2]))
						{
							$stmt->bindParam($parameter, $val[0], $val[1], $val[2]);
						}
						elseif (isset($val[1]))
						{
							$stmt->bindParam($parameter, $val[0], $val[1]);
						}
						else
						{
							$stmt->bindParam($parameter, $val[0]);
						}
					}
					else
					{
						$stmt->bindParam($parameter, $val);
					}
				}
			}
			else
			{
				$stmt->bindParam(1, $columns);
			}
		}

		$stmt->execute();

		switch ($type)
		{
			case '1':
				$result = $stmt->fetch($this->fetch_mode);
				break;

			case '2':
				$result = $stmt->fetchAll($this->fetch_mode);
				break;

			case '3':
				$result = (preg_match('/^SELECT/i' , trim($sql))) ? count($stmt->fetchAll($this->fetch_mode)) : $stmt->rowCount();
				break;

			case '4':
				$result = $stmt;
				break;
		}

		return $result;
	}

	/**
	 * 執行 SQL Execute
	 *
	 * @param string $sql	// SQL 語法
	 */
    public function Execute($sql)
	{
		$result = $this->conn->exec($sql);

        return $result;
    }

	/**
	 * 執行 SQL Query
	 *
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 */
    public function Query($sql, $columns = array())
	{
		if ($columns)
		{
			// DELETE 時，type = 3 返回影響行數
			$type = (preg_match('/^SELECT/i' , trim($sql))) ? '4' : '3';

			$result = $this->_fetch($type, $sql, $columns);
		}
		else
		{
			$result = $this->conn->query($sql);
		}

        return $result;
    }

	/**
	 * 取得 SQL Query
	 *
	 * @param object $res	// SQL Query Object
	 */
	public function fetchRow($res)
	{
		return $res->fetch($this->fetch_mode);
	}

	/**
	 * 取得單筆資料
	 *
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 */
    public function getRow($sql, $columns = array())
	{
        return $this->_fetch('1', $sql, $columns);
    }

	/**
	 * 取得所有資料
	 *
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 */
    public function getArray($sql, $columns = array())
	{
        return $this->_fetch('2', $sql, $columns);
    }

	/**
	 * 取得資料總筆數
	 *
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 */
    public function getNum($sql, $columns = array())
	{
        return $this->_fetch('3', $sql, $columns);
    }

	/**
	 * 取得 prepare 完整 SQL
	 *
	 * @param string $sql		// SQL 語法
	 * @param mixed $columns	// 資料
	 */
	public function getSql($sql, $columns)
	{
        $indexed = $columns == array_values($columns);

        foreach($columns as $key => $val)
		{
			$val_set = $val;
			if (is_array($val))
			{
				$val_set = $val[0];
			}
			$val_set = "'". $val_set ."'" ;

            if ($indexed)
			{
				$sql = preg_replace('/\?/', $val_set, $sql, 1);
			}
            else
			{
				$sql = str_replace(':'.$key, $val_set, $sql);
			}
        }
        return $sql;
    }

	/**
	 * 自動執行 INSERT 和 UPDATE
	 *
	 * @param string $table	// 資料表名稱
	 * @param mixed $columns	// 資料，array(parameter, variable, data_type, length)
	 * @param string $mode		// 執行模式 INSERT，UPDATE
	 * @param string $where	// UPDATE WHERE SQL
	 */
    public function autoExecute($table, $columns, $mode = 'INSERT', $where = FALSE)
	{
		$mode = strtoupper($mode);

		// UPDATE 模式下是否有 WHERE SQL
		if ($where === FALSE && $mode == 'UPDATE')
		{
			return FALSE;
		}

		$columns_sql = array();
		foreach ($columns as $key => $val)
		{
			$columns_sql[] = $key .' = :'.$key;
			$columns_key[] = $key;
			$columns_val[] = ':'. $key;
		}

		switch($mode)
		{
			case 'UPDATE':
				$sql = 'UPDATE '. $table . ' SET '. implode(',', $columns_sql) . ' WHERE ' . $where;
				break;

			case 'INSERT':
				$sql = 'INSERT INTO '. $table . ' ('. implode(',', $columns_key) .') VALUES ('. implode(',', $columns_val) .')';
				break;

			default:
				return FALSE;
		}

		$stmt = $this->conn->prepare($sql);

		foreach ($columns as $key => &$val)
		{
			if (is_array($val))
			{
				if (isset($val[1]) && isset($val[2]))
				{
					$stmt->bindParam(':'.$key, $val[0], $val[1], $val[2]);
				}
				elseif (isset($val[1]))
				{
					$stmt->bindParam(':'.$key, $val[0], $val[1]);
				}
				else
				{
					$stmt->bindParam(':'.$key, $val[0]);
				}
			}
			else
			{
				$stmt->bindParam(':'.$key, $val);
			}
		}

		if ($stmt->execute())
		{
			return ($mode == 'INSERT') ? $this->conn->lastInsertId() : TRUE;
		}
		else
		{
			return FALSE;
		}
    }

    // 紀錄 log
    public function SetLog($userQuery, $userId = '', $userType = '')
    {
    	$record = array();
    	$record["userQuery"] 	= $userQuery;
    	$record["userId"]  		= ($userId) ? $userId : (isset($_SESSION['TW_MemberID'])) ? $_SESSION['TW_MemberID'] : 0;
    	$record["userType"]  	= ($userType) ? $userType : (isset($_SESSION['TW_Level'])) ? $_SESSION['TW_Level'] : 'not';
    	$record["ip"]  			= ip();
    	$record["createTime"]  	= date('Y-m-d H:i:s');

    	// $this->autoExecute('log', $record, 'INSERT');
    }

    // 新增資料庫
    public function AddDB($table, $columns, $id = NULL)
    {
    	global $_conn;

    	// 取得新增 SQL 語法
    	$columns_sql = array();
    	foreach ($columns as $key => $val)
    	{
    		$columns_sql[] = $key .' = :'.$key;
    	}
    	$sql_prepare = 'INSERT INTO '. $table . ' SET '. implode(',', $columns_sql);
    	$sql = $this->getSql($sql_prepare, $columns);

    	// 新增資料
    	$insert_Id = $this->autoExecute($table, $columns, 'INSERT');

    	// 成功時，新增 Log 紀錄
    	if ($insert_Id)
    	{
    		$this->SetLog($sql);
    	}

    	return $insert_Id;
    }

    // 更新資料庫
    public function UpdateDB($table, $columns, $where)
    {
    	// 取得更新 SQL 語法
    	$columns_sql = array();
    	foreach ($columns as $key => $val)
    	{
    		$columns_sql[] = $key .' = :'.$key;
    	}
    	$sql_prepare = 'UPDATE '. $table . ' SET '. implode(',', $columns_sql) . ' WHERE ' . $where;
    	$sql = $this->getSql($sql_prepare, $columns);

    	// 更新資料
    	$res = $this->autoExecute($table, $columns, 'UPDATE', $where);

    	// 成功時，新增 Log 紀錄
    	if ($res && $sql)
    	{
    		$this->SetLog($sql);
    	}

    	return $res;
    }

    // 刪除資料庫
    public function DeleteDB($sql, $columns = array())
    {

    	// 刪除資料
    	$res = $this->Query($sql, $columns);

    	// 成功時，新增 Log 紀錄
    	if ($res)
    	{
    		$this->SetLog($sql);
    	}

    	return $res;
    }
}
