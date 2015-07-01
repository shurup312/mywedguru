<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 07.05.15
 * Time: 17:29
 */


namespace system\core\exceptions;

use system\core\App;

class NotAuthorizedException extends \Exception {

	public function __construct(){
		header('Location: '.App::getConfig()['loginURL'].'?redirectTo='.$_SERVER['REQUEST_URI']);
	}
} 
