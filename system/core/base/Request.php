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
		if(!isset($_POST[$name])){
			return null;
		}
		return $_POST[$name];
	}

	public function files($name = false)
	{
		if(!$name){
			return $_FILES;
		}
		if(!isset($_FILES[$name])){
			return null;
		}
		return $_FILES[$name];
	}

	public function raw()
	{
		return file_get_contents('php://input');
	}

	public function requestMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}
 
?>
