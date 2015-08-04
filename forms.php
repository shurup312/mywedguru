<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 24.06.2015
 * Time: 19:55
 */
define("_ROOT_PATH_", __DIR__.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR);
require __DIR__.DIRECTORY_SEPARATOR."system".DIRECTORY_SEPARATOR."boot.php";
use system\core\App;

App::go();
$out     = fopen('php://stdout', 'w');
$modules = new Modules();
$forms   = new Forms();
Console::printModules($modules);
fscanf(STDIN, '%d\n', $moduleID);
if (!$modules->checkModule($moduleID)) {
	echo 'No module exists';
	die();
}
do {
	Console::printForm($forms);
	Console::printElements($forms);
	fscanf(STDIN, '%d\n', $elementID);
	if (!$elementID) {
		break;
	}
	Console::printGetName();
	fscanf(STDIN, '%s\n', $elementName);
	if (!$elementName) {
		continue;
	}
	$forms->addElement($elementID, $elementName);
	do {
		Console::printValidators($forms);
		fscanf(STDIN, '%s\n', $validator);
		if (!$validator) {
			break;
		}
		$forms->addValidator($elementName, $validator);
		echo 'Validator added. Validators list for element '.$elementName.' '.$forms->printValidatorsForElement($elementName).PHP_EOL;
	} while($validator);
} while($elementID!=0);
$forms->createForm($modules->modules[$moduleID - 1]);

class Modules
{

	public function __construct()
	{
		$this->modules = App::getConfig()['modules'];
		$this->modules = $this->getModules([], '', $this->modules);
	}

	public function printModules()
	{
		$list = '';
		foreach ($this->modules as $key => $module) {
			$list .= ($key + 1).') '.$module.PHP_EOL;
		}
		return $list;
	}

	private function getModules($list = [], $prefix = '', $array = [])
	{
		foreach ($array as $name => $module) {
			$list[] = $prefix.$name;
			if (isset($module['modules'])) {
				$list = $this->getModules($list, $name.'/', $module['modules']);
			}
		}
		return $list;
	}

	public function checkModule($moduleID)
	{
		return isset($this->modules[$moduleID]);
	}
}

class Forms
{

	const TAG_INPUT_TEXT = 1;
	const TAG_INPUT_NUMBER = 2;
	const TAG_INPUT_EMAIL = 3;
	const TAG_INPUT_FILE = 4;
	const TAG_SELECT = 5;
	const TAG_TEXTAREA = 6;
	const TAG_INPUT_SU8MIT = 7;
	const VALIDATOR_BETWEEN = 1;
	const VALIDATOR_LESS = 2;
	const VALIDATOR_GREAT = 3;
	const VALIDATOR_ARRAY = 4;
	const VALIDATOR_NOTEMPTY = 5;
	const VALIDATOR_REGEX = 6;
	const VALIDATOR_STRING_LENGTH = 7;
	public $elements = [
		self::TAG_INPUT_TEXT   => 'Input [type=text]',
		self::TAG_INPUT_NUMBER => 'Input [type=number]',
		self::TAG_INPUT_EMAIL  => 'Input [type=email]',
		self::TAG_INPUT_FILE   => 'Input [type=file]',
		self::TAG_SELECT       => 'Select',
		self::TAG_TEXTAREA     => 'Textarea',
	];
	public $formElements;
	public $validators = [
		self::VALIDATOR_BETWEEN       => 'Between',
		self::VALIDATOR_LESS          => 'Less than',
		self::VALIDATOR_GREAT         => 'Greater than',
		self::VALIDATOR_ARRAY         => 'In array',
		self::VALIDATOR_NOTEMPTY      => 'Not empty',
		self::VALIDATOR_REGEX         => 'Regex',
		self::VALIDATOR_STRING_LENGTH => 'String length',
	];

	public function printElements()
	{
		$list = '';
		foreach ($this->elements as $id => $element) {
			$list .= $id.') '.$element.PHP_EOL;
		}
		$list .= '0) Exit'.PHP_EOL;
		return $list;
	}

	public function addElement($type, $name)
	{
		$this->formElements[$name] = [
			'type'       => $type,
			'name'       => $name,
			'validators' => [],
		];
	}

	public function printValidators()
	{
		$list = '';
		foreach ($this->validators as $id => $element) {
			$list .= $id.') '.$element.PHP_EOL;
		}
		$list .= '0) Exit'.PHP_EOL;
		return $list;
	}

	public function addValidator($elementName, $validator)
	{
		$this->formElements[$elementName]['validators'][] = $validator;
	}

	public function printValidatorsForElement($name)
	{
		$list = [];
		foreach ($this->formElements[$name]['validators'] as $validatorID) {
			$list[] = $this->validators[$validatorID];
		}
		if (!$list) {
			return 'empty';
		}
		return implode(', ', $list);
	}

	public function createForm($module)
	{
		$modulePath = $this->getModulePath($module);
		$this->createFolders($modulePath);
		$this->createFiles($modulePath);
	}

	/**
	 * @param $modulePath
	 */
	private function createFolders($modulePath)
	{
		@mkdir($modulePath.DIRECTORY_SEPARATOR.'forms');
		@mkdir($modulePath.DIRECTORY_SEPARATOR.'validators');
	}

	/**
	 * @param $module
	 * @param $formName
	 */
	private function createFiles($module)
	{
		$formPath      = $module.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'AddForm.php';
		$validatorPath = $module.DIRECTORY_SEPARATOR.'validators'.DIRECTORY_SEPARATOR.'AddValidator.php';
		file_put_contents($formPath, $this->getFormContent($module));
		file_put_contents($validatorPath, $this->getValidatorContent($module));
	}

	/**
	 * @param $module
	 *
	 * @return bool|string
	 * @throws Exception
	 */
	private function getModulePath($module)
	{
		$path = 'webapp';
		foreach (explode('/', $module) as $subModule) {
			$path .= '.modules.'.$subModule;
		}
		$modulePath = App::getPathOfAlias($path);
		return $modulePath;
	}

	/**
	 * @param $module
	 *
	 * @return mixed
	 */
	private function getFormContent($module)
	{
		$namespace = explode('webapp', $module);
		$namespace = 'webapp'.str_replace(DIRECTORY_SEPARATOR, '\\', $namespace[1]).'forms';
		$content   = '';
		foreach ($this->formElements as $name => $element) {
			$content .= $this->getContentForElement($element);
		}
		$content .= $this->getContentForElement(['type' => self::TAG_INPUT_SU8MIT]);
		$formContent = file_get_contents('formsTemplate.tpl');
		$formContent = str_replace('{classname}', 'AddForm', $formContent);
		$formContent = str_replace('{namespace}', $namespace, $formContent);
		$formContent = str_replace('{elements}', $content, $formContent);
		return $formContent;
	}

	/**
	 * @param $module
	 * @param $formName
	 *
	 * @return mixed
	 */
	private function getValidatorContent($module)
	{
		$namespace = explode('webapp', $module);
		$namespace = 'webapp'.str_replace(DIRECTORY_SEPARATOR, '\\', $namespace[1]).'validators';
		$content   = '';
		foreach ($this->formElements as $name => $element) {
			if ($element['validators']) {
				foreach ($element['validators'] as $validatorID) {
					$content .= $this->getContentForValidator($element['name'], $validatorID);
				}
			}
		}
		$formContent = file_get_contents('validatorsTemplate.tpl');
		$formContent = str_replace('{classname}', 'AddValidator', $formContent);
		$formContent = str_replace('{namespace}', $namespace, $formContent);
		$formContent = str_replace('{elements}', $content, $formContent);
		return $formContent;
	}

	private function getContentForElement($element)
	{
		switch ($element['type']) {
			case self::TAG_INPUT_TEXT:
				return "
					'".$element['name']."'           => [
						'type'       => InputTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'      => '".$element['name']."',
							'type'      => 'text',
						],
					],";
			case self::TAG_INPUT_NUMBER:
				return "
					'".$element['name']."'           => [
						'type'       => InputTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'      => '".$element['name']."',
							'type'      => 'number',
						],
					],";
			case self::TAG_INPUT_EMAIL:
				return "
					'".$element['name']."'           => [
						'type'       => InputTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'      => '".$element['name']."',
							'type'      => 'email',
						],
					],";
			case self::TAG_INPUT_FILE:
				return "
					'".$element['name']."'           => [
						'type'       => InputTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'      => '".$element['name']."',
							'type'      => 'file',
						],
					],";
			case self::TAG_SELECT:
				return "
					'".$element['name']."'           => [
						'type'       => SelectTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'    => '".$element['name']."',
							'value'   => null,
							'options' => [

							],
						],
					],";
			case self::TAG_TEXTAREA:
				return "
					'".$element['name']."'           => [
						'type'       => TextareaTag::className(),
						'label'      => '".$element['name']."',
						'attributes' => [
							'name'      => '".$element['name']."',
							'maxlength' => 1024,
						],
					],";
			case self::TAG_INPUT_SU8MIT:
				return "
					'submit'           => [
						'type'       => InputTag::className(),
						'label'      => '',
						'attributes' => [
							'type'  => 'submit',
							'value' => 'Отправить',
						],
					],";
		}
	}

	private function getContentForValidator($elementName, $validatorID)
	{
		switch ($validatorID) {
			case self::VALIDATOR_ARRAY:
				return "
					[
					    '".$elementName."',
					    'inArray',
					    'haystack' => [

					    ],
					],";
			case self::VALIDATOR_BETWEEN:
				return "
					[
						'".$elementName."',
						'between',
						'min' => 0,
						'max' => 1,
						'inclusive' => false,
					],";
			case self::VALIDATOR_GREAT:
				return "
					[
						'".$elementName."',
						'greaterThan',
						'min' => 0,
						'inclusive' => false,
					],";
			case self::VALIDATOR_LESS:
				return "
					[
						'".$elementName."',
						'lessThan',
						'max' => 1,
						'inclusive' => false,
					],";
			case self::VALIDATOR_NOTEMPTY:
				return "
					[
						'".$elementName."',
						'notEmpty',
					],";
			case self::VALIDATOR_REGEX:
				return "
					[
						'".$elementName."',
						'regex',
						'pattern' => '',
					],";
			case self::VALIDATOR_STRING_LENGTH:
				return "
					[
						'".$elementName."',
						'stringLength',
						'min' => 3,
						'max' => 5,
					],";
		}
	}
}

class Console
{

	public static function printForm($forms)
	{
		echo PHP_EOL.'------------------------------------------'.PHP_EOL;
		if ($forms->formElements) {
			$i = 0;
			foreach ($forms->formElements as $element) {
				echo (++$i).') element '.$forms->elements[$element['type']].', name "'.$element['name'].'"';
				if ($element['validators']) {
					echo '(validators ';
					echo $forms->printValidatorsForElement($element['name']);
					echo ')';
				}
				echo PHP_EOL;
			}
			echo '------------------------------------------'.PHP_EOL;
		}
	}

	public static function printElements($forms)
	{
		echo PHP_EOL.'Select type element for form:'.PHP_EOL;
		echo $forms->printElements();
		echo 'Element:';
	}

	public static function printGetName()
	{
		echo PHP_EOL.'Set element name:'.PHP_EOL;
		echo 'Name:';
	}

	public static function printValidators($forms)
	{
		echo PHP_EOL.'Add validator to element:'.PHP_EOL;
		echo $forms->printValidators();
		echo 'Validator:';
	}

	public static function printModules($form)
	{
		echo 'Select module to make form:'.PHP_EOL;
		echo $form->printModules();
		echo 'Module:';
	}
}
