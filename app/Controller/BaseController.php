<?php
namespace App\Controller;

use Yen\Controller;

class BaseController extends Controller
{
	private $error = array();

	public function index()
	{
		$this->assign( 'title' , 'My Framework' );
		$this->render();
	}
}