<?php
use yii\db\Schema;
use yii\db\Migration;

class m150901_121955_AddTable_Wedding extends Migration
{

    public $tableName = '{{%wedding}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions
                = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'groom_id'     => $this->integer()->notNull().' COMMENT "Жених"',
            'bride_id'     => $this->integer()->notNull().' COMMENT "Невеста"',
            'date_created' => $this->timestamp()->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
            'date'         => $this->timestamp()->notNull().' COMMENT "Дата свадьбы"',
        ], $tableOptions);
        $this->addForeignKey('fk_wedding_bride', $this->tableName, 'bride_id', 'person', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_wedding_groom', $this->tableName, 'groom_id', 'person', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
