<?php

namespace Yen;

/**
 * Controller
 */
class Controller
{
	protected $_controller;
	protected $_action;
	protected $_view;

	function __construct($controller, $action)
	{
		$this->_controller = $controller;
		$this->_action =  $action;
		$this->_view = new View ($controller, $action);
	}

	// 分配變量
    public  function  assign ($name, $value)
    {
        // 把變量保存到 View
        $this ->_view->assign($name, $value);
    }

    // 渲染視圖
    public  function  render ()
    {
        $this ->_view->render();
    }

    // 指定視圖文件
    public function template($template)
    {
    	$this->_view->template($template);
    }
}