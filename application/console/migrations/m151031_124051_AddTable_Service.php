<?php
use yii\db\Schema;
use yii\db\Migration;

class m151031_124051_AddTable_Service extends Migration
{

    public $tableName = '{{%service}}';

    public function up()
    {
        $this->createServiceTable();
        $this->addDefaultPhotoService();
    }

    private function createServiceTable()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions
                = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'        => $this->primaryKey(),
            'user_type' => $this->integer()->notNull().' COMMENT "Тип пользователя, для которого услуга предоставляется"',
            'name'      => $this->text()->notNull().' COMMENT "Название услуги"',
        ], $tableOptions);
    }

    private function addDefaultPhotoService()
    {
        $this->insert($this->tableName, [
            'user_type' => \domain\person\values\UserType::USER_PHOTOGRAPGER,
            'name'      => 'Минимальный пакет',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
