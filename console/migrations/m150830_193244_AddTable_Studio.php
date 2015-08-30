<?php
use yii\db\Schema;
use yii\db\Migration;

class m150830_193244_AddTable_Studio extends Migration
{

    public $tableName = '{{%studio}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName==='mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'name'         => $this->string(128)
                                   ->notNull().' COMMENT "Название студии"',
            'phone'        => $this->string(32).' NULL DEFAULT NULL COMMENT "Телефон"',
            'address'      => $this->string().' NULL DEFAULT NULL COMMENT "Адрес"',
            'date_created' => $this->timestamp()
                                   ->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
