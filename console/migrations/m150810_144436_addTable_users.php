<?php

use yii\db\Schema;
use yii\db\Migration;

class m150810_144436_addTable_users extends Migration
{

    public $tableName = '{{%users}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'rights' => $this->integer()->notNull().' COMMENT "Права пользователя"',
            'site' => $this->integer()->notNull().' COMMENT "ID соцсети"',
            'socialid' => $this->string(64).' COMMENT "ID пользователя в соцсети"',
            'token' => $this->string(512)->notNull().' COMMENT "Токен для доступа к соц.сети"',
            'status' => $this->integer()->notNull()->defaultValue(1).' COMMENT "Статус регистрации пользователя"',
            'user_type' => $this->integer().' NULL DEFAULT NULL COMMENT "Тип пользователя"',
            'date_created' => $this->timestamp()->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
