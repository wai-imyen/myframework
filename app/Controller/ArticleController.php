<?php
namespace App\Controller;

use Yen\Controller;
use App\Model\ArticleModel;
use Requests;

class ArticleController extends Controller
{
	private $error = array();

	public function index()
	{	
		$model = new ArticleModel();
		$articles = $model->get_all_articles();

		$this->assign( 'title' , 'Article' );
		$this->assign( 'datas' , $articles );
		$this->template('article/index');
		$this->render();
	}

	// 顯示文章
	public function show()
	{	
		$id = $_GET['id'];

		$model = new ArticleModel();
		$article = $model->get_article($id);

		$this->assign( 'title' , 'Article Content' );
		$this->assign( 'data' , $article );
		$this->template('article/show');
		$this->render();
	}

	// 新增文章
	public function create()
	{	
		$model = new ArticleModel();

		$this->assign( 'title' , 'Article Add' );
		$this->assign( 'mode' , 'create' );
		$this->template('article/edit');
		$this->render();
	}

	// 編輯文章
	public function edit()
	{	
		$id = $_GET['id'];

		$model = new ArticleModel();
		$article = $model->get_article($id);

		$this->assign( 'title' , 'Article Edit' );
		$this->assign( 'data' , $article );
		$this->assign( 'mode' , 'update' );
		$this->template('article/edit');
		$this->render();
	}

	// 儲存文章
	public function save()
	{	
		$model = new ArticleModel();

		// 表單資料
		$columns = array();
		$columns['title'] = $_POST['title'];
		$columns['content'] = $_POST['content'];
		$columns['created_at'] = date('Y-m-d H:i:s');

		// 儲存模式
		switch ($_POST['mode']) 
		{	
			// 新增文章
			case 'create':
				if ($id = $model->add_article($columns)) 
				{
					alert('新增成功！', HOST_URL . 'article/show/' . $id);
				}
				break;

			// 更新文章
			case 'update':

				// 取得 id
				$where = 'id = ' . $_GET['id'];

				if ($model->update_article($columns, $where)) 
				{
					alert('更新成功！', HOST_URL . 'article/show/' . $_GET['id']);
				}
				break;
		}

		alert('操作失敗！', 'back');
	}

	// 刪除文章
	public function delete()
	{	
		$model = new ArticleModel();

		// 取得 id
		$where = 'id = ' . $_GET['id'];

		if ($model->delete_article($where)) 
		{
			alert('刪除成功！', HOST_URL . 'article');
		}
		else
		{
			alert('操作失敗！', 'back');
		}
	}
}