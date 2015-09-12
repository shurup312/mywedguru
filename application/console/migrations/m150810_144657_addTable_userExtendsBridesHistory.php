<?php

use yii\db\Schema;
use yii\db\Migration;

class m150810_144657_addTable_userExtendsBridesHistory extends Migration
{
    public $tableName = '{{%user_extends_brides_history}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull().' COMMENT "ID пользователя"',
            'action_user_id' => $this->integer()->notNull().' COMMENT "ID кто заапрувил или отменил"',
            'first_name' => $this->string().' NULL DEFAULT NULL COMMENT "Имя"',
            'fiance_first_name' => $this->string().' NULL DEFAULT NULL COMMENT "Имя жениха"',
            'last_name' => $this->string().' NULL DEFAULT NULL COMMENT "Фамилия"',
            'fiance_last_name' => $this->string().' NULL DEFAULT NULL COMMENT "Фамилия жениха"',
            'avatar' => $this->string(64),
            'date_wedding' => $this->timestamp().' NULL DEFAULT NULL',
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
