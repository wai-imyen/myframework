<?php
date_default_timezone_set("Asia/Taipei");
session_start();

// DEBUG
define('APP_DEBUG', TRUE);

// 根目錄
define('ROOT_PATH', str_replace('\\', '/', realpath(dirname(__FILE__))) .'/');

// 系統目錄
define('DIR_SYSTEM', ROOT_PATH .'system/');

// MVC目錄
define('DIR_APP', ROOT_PATH .'app/');

// 設定
$_config = require(ROOT_PATH .'config.php');

// 網域
define('HOST_URL', $_config['protocol'] . $_SERVER['HTTP_HOST'] .'/');

// 共用目錄網址
define('PUBLIC_URL', HOST_URL .'public/');

// Composer autoload
require(ROOT_PATH .'vendor/autoload.php');

// 框架
require(ROOT_PATH .'system/Framework.php');

// 輔助函式
include(DIR_SYSTEM .'helpers/function.php');

// 啟動框架
(new Yen\Framework($_config))->run();

