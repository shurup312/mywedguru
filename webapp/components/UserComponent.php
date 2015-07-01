<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.05.15
 * Time: 17:41
 */


namespace webapp\components;

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
		if(!$this->user){
			$this->user = User::findOne($this->id);
		}
		return isset($this->user->$name)?$this->user->$name:null;
	}

} 
