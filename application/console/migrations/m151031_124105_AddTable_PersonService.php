<?php
use yii\db\Schema;
use yii\db\Migration;

class m151031_124105_AddTable_PersonService extends Migration
{

    public $tableName = '{{%person_service}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'person_id'    => $this->integer()->notNull().' COMMENT "Пользователь"',
            'service_id'   => $this->integer()->notNull().' COMMENT "Услуга"',
            'hours'        => $this->integer()->notNull().' COMMENT "Количество часов"',
            'cost'         => $this->integer()->notNull().' COMMENT "Стоимость"',
            'date_created' => $this->timestamp().' NULL DEFAULT NULL',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_personService_person', $this->tableName, 'person_id', 'person', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_personService_service', $this->tableName, 'service_id', 'service', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
