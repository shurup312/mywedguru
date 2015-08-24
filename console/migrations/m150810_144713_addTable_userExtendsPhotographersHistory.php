<?php

use yii\db\Schema;
use yii\db\Migration;

class m150810_144713_addTable_userExtendsPhotographersHistory extends Migration
{
    public $tableName = '{{%user_extends_photographers_history}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull().' COMMENT "ID пользователя"',
            'action_user_id' => $this->integer()->notNull().' COMMENT "ID кто заапрувил или отменил"',
            'first_name' => $this->string().' NULL DEFAULT NULL COMMENT "Имя"',
            'last_name' => $this->string().' NULL DEFAULT NULL COMMENT "Фамилия"',
            'studio_name' => $this->string().' NULL DEFAULT NULL COMMENT "Название студии"',
            'site_name' => $this->string().' NULL DEFAULT NULL COMMENT "URL сайта"',
            'email' => $this->string().' NULL DEFAULT NULL COMMENT "E-mail"',
            'phone' => $this->string().' NULL DEFAULT NULL COMMENT "Телефон"',
            'avatar' => $this->string(64).' NULL DEFAULT NULL COMMENT "Аватарка"',
            'status' => $this->integer()->notNull(),
            'date_created' => $this->timestamp()->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
