<?php
use yii\db\Schema;
use yii\db\Migration;

class m150901_134811_AddTable_Photogallery extends Migration
{

    public $tableName = '{{%photogallery}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions
                = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer()->notNull().' COMMENT "Пользователь"',
            'name'         => $this->string()->notNull().' COMMENT "Название"',
            'date_created' => $this->timestamp()->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_photogallery_user', $this->tableName, 'user_id', 'users', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
