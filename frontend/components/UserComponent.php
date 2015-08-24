<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.05.15
 * Time: 17:41
 */

namespace frontend\components;

use frontend\models\User;
use yii\base\Object;

class UserComponent extends Object{
	private $id;
	private $user;
	public function __construct(){
		if(\yii::$app->session->get('USER') && isset(\yii::$app->session->get('USER')['id'])){
			$this->id  = \yii::$app->session->get('USER')['id'];
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
