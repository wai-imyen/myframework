<?php
namespace Yen;

/**
 * View
 */
class View
{
	protected $data = array ();
	protected $_controller;
	protected $_action;
	protected $template = array();
	
	function __construct($controller, $action)
	{
		$this->_controller = $controller;
		$this->_action =  $action;
	}

	// 分配變變量
	public function assign($name, $value)
	{
		$this ->data[$name] = $value;
	}

	public function template($template)
	{
		$this->template[] = $template;
	}

	public function render()
	{	
		// 將陣列中的索引值提出來當變數，組成相對應的值
		extract($this->data);

		$defaultHeader = DIR_APP . 'View/base/_header.php' ;
		$defaultFooter = DIR_APP . 'View/base/_footer.php' ;

		$controllerHeader = DIR_APP . 'View/' . $this ->_controller . '/_header.php' ;
		$controllerFooter = DIR_APP . 'View/' . $this ->_controller . '/_footer.php' ;
		$controllerLayout = DIR_APP . 'View/' . $this ->_controller . '/' . $this ->_action . '.php' ;

		// 載入 header
		if (is_file($controllerHeader)) 
		{
		    include ($controllerHeader);
		} 
		else 
		{
		    include ($defaultHeader);
		}

		// 判斷視圖文件是否存在
		$views = $this->template;

		if ($views)
		{
			foreach ($views as $view)
			{
				$controllerLayout = DIR_APP . 'View/' . $view . '.php';

				if (file_exists($controllerLayout))
				{
					include ($controllerLayout);
				}
				else
				{
					echo ($view . '視圖文件不存在<br />');
				}
			}
		}
		else if (is_file($controllerLayout)) 
		{
		    include ($controllerLayout);
		} 
		else 
		{
		    echo  "無視圖文件 " . $controllerLayout ;
		}

		// 載入 footer
		if (is_file($controllerFooter)) 
		{
		    include ($controllerFooter);
		} 
		else 
		{
		    include ($defaultFooter);
		}
	}
}