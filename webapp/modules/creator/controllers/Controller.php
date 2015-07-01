<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 14.05.15
 * Time: 15:00
 */
namespace webapp\modules\creator\controllers;

use system\core\App;
use system\core\base\View;
use system\core\ORM;
use webapp\modules\creator\forms\CreatorForm;

class Controller extends \system\core\Controller
{

	public $design = 'admin';
	private $columns = [];
	public function init()
	{
		View::setDesignParams(['header'=>'Создание модулей']);
		View::setDesign('admin');
	}

	public function actionIndex()
	{
		$formName = 'creatorForm';
		$form     = new CreatorForm($formName);
		if (isset($_POST[$formName])) {
			$form->load($_POST[$formName]);
			$name = $form->name;
			$path = _ROOT_PATH_.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'webapp'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR;
			$this->createFolders($path, $name);
			$this->createForm($form, $path);
			$this->createController($form, $path);
			$this->createModel($form, $path);
			$this->createViews($form, $path);
		}
		return View::withDesign('index', ['form' => $form,]);
	}

	/**
	 * @param $path
	 * @param $name
	 *
	 * @return string
	 */
	private function createFolders($path, $name)
	{
		$array = [
			'controllers',
			'models',
			'views',
			'forms',
		];
		foreach ($array as $item) {
			if (!file_exists($path.$name.DIRECTORY_SEPARATOR.$item)) {
				mkdir($path.$name.DIRECTORY_SEPARATOR.$item, 0777, true);
			}
		}
	}

	/**
	 * @param $form
	 * @param $path
	 *
	 */
	private function createForm($form, $path)
	{
		$name      = $form->name;
		$tableName = $form->table;
		$sth       = ORM::getConnection()
						->query("SELECT
						    *
						FROM
						    information_schema.columns
						WHERE
						    table_name = '$tableName'
						    AND table_schema = '".App::getConfig()['db']['name']."'");
		$fields    = [];
		foreach ($sth->fetchAll() AS $row) {
			if (!$row['COLUMN_KEY']) {
				$type = 'text';
				if ($row['DATA_TYPE'] == 'int') {
					$type = 'number';
				}
				$this->columns[$row['COLUMN_NAME']] = $row['COLUMN_COMMENT'];
				$fields[] = "'$row[COLUMN_NAME]'      => [
				'type'       => ".(isset($row['CHARACTER_MAXIMUM_LENGTH']) && $row['CHARACTER_MAXIMUM_LENGTH']>256?'TextareaTag':'InputTag')."::className(),
				'label'      => '".$row['COLUMN_COMMENT']."',
				'attributes' => [
					'type'      => '$type',
					'name'      => '$row[COLUMN_NAME]',\r\n".
					($row['IS_NULLABLE']=='NO'?
"					'required'  => 1,\r\n":'').
					(isset($row['CHARACTER_MAXIMUM_LENGTH']) && $type!='number' && $row['CHARACTER_MAXIMUM_LENGTH']<=1024?
"					'maxlength' => '".$row['CHARACTER_MAXIMUM_LENGTH']."',\r\n":'').
"					'class'     => 'form-control',\r\n".
					(isset($row['CHARACTER_MAXIMUM_LENGTH']) && $row['CHARACTER_MAXIMUM_LENGTH']>1024 && $row['CHARACTER_MAXIMUM_LENGTH']<=2048?
"					'rows'     => '4',\r\n":'').
					(isset($row['CHARACTER_MAXIMUM_LENGTH']) && $row['CHARACTER_MAXIMUM_LENGTH']>2048 && $row['CHARACTER_MAXIMUM_LENGTH']<=4096?
"					'rows'     => '5',\r\n":'').
					(isset($row['CHARACTER_MAXIMUM_LENGTH']) && $row['CHARACTER_MAXIMUM_LENGTH']>4096?
"					'rows'     => '6',\r\n":'').
"				],
			],";

			}
		}
		$fields[] = "'submit'      => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'value' => 'Сохранить',
					'class' => 'btn btn-success',
				],
			],";
		$formClassName = $this->getFormClassName($name);
		$data          = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'Form.tpl');
		$data          = str_replace('{FormName}', $formClassName, $data);
		$data          = str_replace('{ModuleName}', $name, $data);
		$data          = str_replace('{Fields}', implode("\r\n			", $fields), $data);
		file_put_contents($path.$name.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.$formClassName.'.php', $data);
	}

	private function createController($form, $path)
	{
		$formClassName = $this->getFormClassName($form->name);
		$data          = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'Controller.tpl');
		$data          = str_replace('{FormName}', $formClassName, $data);
		$data          = str_replace('{ModuleName}', $form->name, $data);
		$data          = str_replace('{ModelName}', ucfirst($form->table), $data);
		$data          = str_replace('{Title}', $form->title, $data);
		file_put_contents($path.$form->name.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'Controller.php', $data);
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	private function getFormClassName($name)
	{
		$formClassName = ucfirst($name).'Form';
		return $formClassName;
}

	private function createModel($form, $path)
	{
		$data          = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'Model.tpl');
		$data          = str_replace('{ModuleName}', $form->name, $data);
		$data          = str_replace('{ModelName}', ucfirst($form->table), $data);
		$data          = str_replace('{tableName}', $form->table, $data);
		file_put_contents($path.$form->name.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.ucfirst($form->table).'.php', $data);
	}

	private function createViews($form, $path)
	{
		$columnsNames = '';
		$columnsLabels = '';
		foreach ($this->columns as $key => $item) {
			$columnsLabels.='		<th>'.$item."</th>\r\n";
			$columnsNames.='		<td><?=$column[\''.$key."'];?></td>\r\n";
		}
		$data          = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'index.tpl');
		$data          = str_replace('{ModuleName}', $form->name, $data);
		$data          = str_replace('{ModelName}', ucfirst($form->table), $data);
		$data          = str_replace('{columnsNames}', $columnsNames, $data);
		$data          = str_replace('{columnsLabels}', $columnsLabels, $data);
		file_put_contents($path.$form->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'index.php', $data);

		$formClassName = $this->getFormClassName($form->name);
		$data          = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form.tpl');
		$data          = str_replace('{FormName}', $formClassName, $data);
		$data          = str_replace('{ModuleName}', $form->name, $data);
		file_put_contents($path.$form->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form.php', $data);

	}
} 
