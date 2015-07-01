<?php
namespace system\core\base;

class Request
{
	public function isAjax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}else
		{
			return false;
		}
	}

	public function get($name = false)
	{
		if(!$name){
			return $_GET;
		}
		if(!isset($_GET[$name])){
			return null;
		}
		return $_GET[$name];
	}
	public function post($name = false)
	{
		if(!$name){
			return $_POST;
		}
		if(!$_POST[$name]){
			return null;
		}
		return $_POST[$name];
	}
}
 
?>
