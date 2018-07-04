<?php
/**
 * 警告視窗
 *
 * @param string $messages	// 警告消息
 * @param string $url		// 跳轉頁面
 */
if ( ! function_exists('alert'))
{
	function alert($messages = NULL, $url = NULL)
	{
		echo '
			<meta http-equiv=Content-Type content=text/html; charset=utf-8>
			<script language="JavaScript" type="text/JavaScript">
				window.addEventListener("load",function() {
		';

		if ($messages)
		{
			echo 'alert("'. $messages .'");';
		}

		if (trim($url) == 'back')
		{
			echo 'javascript:history.back(-1);';
		}
		elseif ( ! trim($url))
		{
			echo 'location.reload();';
		}
		else
		{
			echo 'location.href="'. $url .'";';
		}

		echo '
				});
			</script>
		';

		if (trim($url))
		{
			exit();
		}
	}
}

/**
 * 取得使用者 IP
 */
if ( ! function_exists('ip'))
{
	function ip()
	{
		if ( ! empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}

/**
 * 分頁初始化
 *
 *	@param array $data
 */
if ( ! function_exists('setPageination'))
{
	function setPageination($data = array())
	{
		$pagination = new Chao\Pagination();

		if ($data)
		{
			foreach ($data as $key => $val)
			{
				$pagination->$key = $val;
			}
		}

		return $pagination->render();
	}
}

/**
 * Log 紀錄
 *
 * @param  string	$message	Log 內容
 * @param  string	$file_name	Log 檔名
 * @param  string	$tag		Log 標籤
 * @param  string	$path		Log 儲存路徑
 */
if ( ! function_exists('my_log'))
{
	function my_log($message, $file_name = 'error.log', $tag = NULL, $path = '')
	{
		$log_dir = DIR_LOG . $path;	// log 目錄

		// 建立多層目錄
		if ( ! is_dir($log_dir))
		{
			mkdir($log_dir, 0775, TRUE);
		}

		$log_file = $log_dir .'/'. $file_name; 	// log 檔案

		// 檢查有無此檔，沒有就建立一個 0775 權限的檔案
		if ( ! file_exists($log_file))
		{
			$fp_log = fopen($log_file, 'w');
			chmod($log_file, 0775);
			fclose($fp_log);
		}

		// Log 內容
		$msg = date('Y-m-d H:i:s');
		$msg .= ($tag) ? "\t".'['. $tag .']'."\n" : "\n";
		$msg .= $message ."\n";

		// 寫入相關 Log 檔
		error_log($msg, 3, $log_file);
	}
}
