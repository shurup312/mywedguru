<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.05.15
 * Time: 17:41
 */


namespace webapp\components;

use system\core\App;
use system\core\base\Component;
use webapp\modules\users\models\User;

class UserComponent extends Component{
	private $id;
	private $user;
	public function __construct(){
		if(isset($_SESSION['USER']) && isset($_SESSION['USER']['id'])){
			$this->id  = $_SESSION['USER']['id'];
		}
	}

	public function __get($name)
	{
		if(!$this->id){
			return false;
		}
		$this->getUserFromTable();
		return $this->user->$name;
	}

	public function is($rights){
		return App::get('user')->rights&$rights;
	}

	public function getUser()
	{
		if(!$this->id){
			return false;
		}
		$this->getUserFromTable();
		return $this->user;
	}

	private function getUserFromTable()
	{
		if (!$this->user) {
			$this->user = User::findOne($this->id);
		}
	}
}
