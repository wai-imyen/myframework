<?php
// MySQL 資料庫設定
$config['db'] = array(
	'host'		=> 'localhost',
	'username' 	=> 'root',
	'password' 	=> '',
	'dbname' 	=> 'myframework'
);

// 預設 controller 、action
$config['defaultController'] = 'Base';
$config['defaultAction'] = 'index';

// HTTP HTTPS
$config['protocol'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";

// SMTP 設定
$config['smtp_set'] = array(
	'service_email' 			=> 'wai.imyen@gmail.com',
	'service_email_name' 		=> 'MyFramework',
	'service_email_password'	=> 'navy1234',
	'service_email_host' 		=> 'smtp.gmail.com',
	'service_email_port' 		=> '465',
	'service_email_secure' 		=> 'ssl',
	'service_email_account' 	=> 'wai.imyen@gmail.com',
	'service_email_smtp_auth' 	=> 'yes'
);

return $config;
