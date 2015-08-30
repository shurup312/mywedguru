<?php
use yii\db\Schema;
use yii\db\Migration;

class m150830_193249_AddTable_StudioOwner extends Migration
{

    public $tableName = '{{%studio_owner}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName==='mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'studio_id'    => $this->integer()
                                   ->notNull().' COMMENT "Студия"',
            'person_id'    => $this->integer().' NULL DEFAULT NULL COMMENT "Владелец"',
            'date_created' => $this->timestamp()
                                   ->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_studioOwner_studio', $this->tableName, 'studio_id', 'studio', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_studioOwner_person', $this->tableName, 'person_id', 'person', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
