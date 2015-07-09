<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 07.07.2015
 * Time: 16:21
 */
namespace system\core\base;

class Session extends Object
{
	private $session;
	private $flash;

	public function __construct()
	{
		$this->init();
	}

	public function get($name = false){
		if(!$name){
			return $this->session;
		}
		if($name){
			if(!isset($this->session[$name])){
				return null;
			}
			return $this->session[$name];
		}
		return null;
	}

	public function getFlash($name)
	{
		if(isset($this->flash[$name])){
			$flash = $this->flash[$name];
			unset($_SESSION['flash'][$name]);
			$this->init();
			return $flash;
		}
		return null;
	}

	public function hasFlash($name)
	{
		if(isset($this->flash[$name])){
			return true;
		}
		return false;
	}

	public function setFlash($name, $content)
	{
		$_SESSION['flash'][$name] = $content;
		$this->init();

	}

	private function init()
	{
		$this->flash   = isset($_SESSION['flash'])?$_SESSION['flash']:[];
		$this->session = $_SESSION;
		unset($this->session['flash']);
	}
}
