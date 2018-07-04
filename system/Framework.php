<?php

namespace Yen;


/**
 * Framework 框架核心
 */
class Framework
{
	protected $_config = array();

	function __construct($config)
	{
		$this->_config = $config;
	}

	// 啟動框架
	public function run()
	{	
		// 加載類別
		// spl_autoload_register( array ( $this , 'loadClass' ));
		$this->setDbConfig();
		$this->route();
	}

	public function loadClass($class)
	{
		$frameworks = DIR_SYSTEM . $class . '.php';
		$controllers = DIR_APPLICATION . 'Controller/' . $class . '.php';
		$models = DIR_APPLICATION . 'Model/' . $class . '.php';

		if (file_exists($frameworks))	//預載框架核心
		{
			// echo $frameworks.'<br/>';
			include $frameworks;
		}
		elseif (file_exists($controllers))	//預載 controller
		{
			// echo $controllers.'<br/>';
			include $controllers;
		}
		elseif (file_exists($models))	//預載 model
		{
			// echo $models.'<br/>';
			include $models;
		}
		else
		{
			echo $class.' 預載錯誤<br/>';
		}

	}

	// 設定資料庫連結
	public function setDbConfig()
	{
		if ($this->_config['db'])
		{
			Model::setDbConfig($this->_config['db']);
		}
	}

	// ROUTE 處理
	public function route()
	{
		// 預設路徑
		$controllerName = $this->_config['defaultController'];
		$actionName = $this->_config['defaultAction'];
		$param = array();

		// 取得目前網址
		$url = $_SERVER['REQUEST_URI'];

		// 清除?之後的內容
        $position = strpos($url, '?');
        $url = ($position === false) ? $url : substr($url, 0, $position);

		// 刪除前後的 "/"
        $url = trim($url, '/');

		if ($url)
		{
            // 使用“/”分割字符串，並保存在數組中
            $urlArray = explode('/', $url);

            // 刪除空的陣列
            $urlArray = array_filter($urlArray);

            // 取得 controller
            $controllerName = ucfirst(strtolower($urlArray[0]));

            // 取得 action
            array_shift($urlArray);
            $actionName = $urlArray ? $urlArray[0] : $actionName;

            // 取得 param
            array_shift($urlArray);
            $param = $urlArray ? $urlArray : array();
        }

		// 判斷 controller 和 action 是否存在
		$controller = 'App\\Controller\\'. $controllerName .'Controller';
		if ( ! class_exists($controller))
		{
			exit($controllerName .' 控制器不存在');
		}
		if ( ! method_exists($controller, $actionName))
		{
			exit($actionName .' 方法不存在');
		}

		// 如果 contoller 和 action 存在，啟動 controller 和對應 action
		$dispatch = new $controller($controllerName, $actionName);

		// $dispatch->$actionName($param);
		call_user_func_array(array($dispatch, $actionName), $param);
	}
}