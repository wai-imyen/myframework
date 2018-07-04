<?php
namespace App\Model;

use Yen\Model;

class ArticleModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	public function _init()
	{
		$this->table = 'articles';
	}

	// 取得所有文章
	public function get_all_articles()
	{
		$sql = 
			'SELECT 
				* 
			FROM 
				articles AS a
			WHERE
				a.status = ""
			ORDER BY
				a.created_at DESC,
				a.id DESC
			';

		$result = $this->_conn->getArray($sql);

		return $result;
	}

	// 取得文章
	public function get_article($id)
	{
		$sql = 
			'SELECT 
				* 
			FROM 
				articles AS a 
			WHERE
				a.id = :id
				AND a.status = ""
			ORDER BY
				a.created_at DESC,
				a.id ASC
			';

		$columns = array();
		$columns['id'] = $id;

		$result = $this->_conn->getRow($sql, $columns);

		return $result;
	}

	// 新增文章
	public function add_article($columns)
	{
		$result = $this->_conn->AddDB($this->table, $columns);

		return $result;
	}

	// 更新文章
	public function update_article($columns, $where)
	{
		$result = $this->_conn->UpdateDB($this->table, $columns, $where);

		return $result;
	}

	// 刪除文章
	public function delete_article($where)
	{	
		$sql = 'DELETE FROM '. $this->table .' WHERE ' . $where;

		$result = $this->_conn->DeleteDB($sql);

		return $result;
	}
}