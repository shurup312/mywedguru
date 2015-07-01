<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.04.15
 * Time: 18:34
 */
namespace system\core\behaviors;

use system\core\App;
use system\core\exceptions\NotAuthorizedException;
use system\core\base\Behavior;
use Exception;

class AccessBehavior extends Behavior
{
	public $ip;
	public $only = [];
	public $rules = [];
	public $callback;
	private $isAllow;

	public function beforeAction()
	{
		$this->checkListOnly();
		$this->checkRights();
		$this->checkCallback();
		$this->checkRules();
		$this->checkIP();
	}

	/**
	 * Проверка существования параметра only в списке параметров. А так же, если оно есть, проверка, что
	 * экшен входит в одобренный список.
	 * @throws \Exception
	 */
	private function checkListOnly()
	{
		if (!is_array($this->only)) {
			throw new Exception('Параметр only в правилах доступа указан в формате отличном от массива.', 500);
		}
		$this->isAllow = true;
		if ($this->only) {
			if (!in_array(
				$this->getOwner()
					 ->getAction(), $this->only
			)
			) {
				throw new NotAuthorizedException('Access denied', 403);
			}
			$this->isAllow = false;
		}

	}

	private function checkRights()
	{
		if(is_null($this->rights)){
			return true;
		}
		$isAllow = false;
		foreach ($this->rights as $rights) {
			if(App::get('user')->rights & $rights){
				$isAllow = true;
			}
		}
		if(!$isAllow){
			throw new NotAuthorizedException('Access denied', 403);
		}
	}

	/**
	 * Накладыание прав на экшен, чтобы получить в итоге, допущен ли экшен для выполнения или нет.
	 */
	private function checkRules()
	{
		if (!is_array($this->rules)) {
			throw new Exception('Параметр rules в правилах доступа указан в формате отличном от массива.', 500);
		}
		foreach ($this->rules as $rule) {
			$this->checkOneRule($rule);
		}
	}

	/**
	 * Накладывание правила на экшен
	 *
	 * @param $rule
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private function checkOneRule($rule)
	{
		if (isset($rule['actions'])) {
			if (!is_array($rule['actions'])) {
				throw new Exception('Параметр actions в правиле доступа указан в формате отличном от массива.', 500);
			}
			if (!in_array($this->getOwner()->getAction(), $rule['actions'])) {
				return true;
			}
		}
		if (!$this->checkRuleRights($rule)){
			throw new NotAuthorizedException('Access denied', 403);
		}
		if(!$this->checkRuleCallback($rule)) {
			throw new NotAuthorizedException('Access denied', 403);
		}
	}

	/**
	 * Вощвращает true, если пользователь не присутствует в списке запрещеных IP
	 * @throws \Exception
	 * @return bool
	 */
	private function checkIP()
	{
		if(is_null($this->ip)){
			return true;
		}
		if(in_array($_SERVER['REMOTE_ADDR'], $this->ip)){
			throw new Exception('Access denied', 403);
		}
	}

	/**
	 * Возвращает результат выполнения кастомного коллбэка, указанного в правилах.
	 * @throws \Exception
	 * @return bool
	 */
	private function checkCallback()
	{
		if(is_null($this->callback)){
			return true;
		}

		if(!call_user_func($this->callback)){
			throw new NotAuthorizedException('Access denied', 403);
		}
	}

	/**
	 * Возвращает true, в случае если текущий пользователь попадает по правам в список разрешенных.
	 * @param $rule
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private function checkRuleRights($rule)
	{
		$isAllow = true;
		if(!isset($rule['rights'])){
			return $isAllow;
		}
		if(!is_array($rule['rights'])){
			throw new Exception('Параметр rights в правиле доступа указан в формате отличном от массива.', 500);
		}
		foreach ($rule['rights'] as $rights) {
			if(App::get('user')->rights & $rights){
				$isAllow = true;
			}
		}
		return $isAllow;
	}

	/**
	 * Возвращает результат выполнения кастомного коллбэка, указанного в конкретном правиле
	 *
	 * @param $rule
	 *
	 * @return bool
	 */
	private function checkRuleCallback($rule)
	{
		$isAllow = true;
		if(!isset($rule['callback'])){
			return $isAllow;
		}
		$isAllow = call_user_func($rule['callback']);
		return $isAllow;
	}

	public $rights;
}
