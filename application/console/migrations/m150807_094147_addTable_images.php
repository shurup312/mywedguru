<?php
use yii\db\Schema;
use yii\db\Migration;

class m150807_094147_addTable_images extends Migration
{

	public $tableName = '{{%images}}';

	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName==='mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		$this->createTable(
			$this->tableName, [
			'id'           => $this->primaryKey(),
			'user_id'      => $this->integer(),
			'path'         => $this->string()
								   ->notNull(),
			'filename'     => $this->string()
								   ->notNull(),
			'date_created' => $this->timestamp(),
			'date_deleted' => $this->timestamp(),
		], $tableOptions
		);
	}

	public function down()
	{
		$this->dropTable($this->tableName);
	}
}
