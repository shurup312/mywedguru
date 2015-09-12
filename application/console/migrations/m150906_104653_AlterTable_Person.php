<?php

use yii\db\Schema;
use yii\db\Migration;

class m150906_104653_AlterTable_Person extends Migration
{
    private $tableName = '{{%person}}';
    public function up()
    {
        $this->addColumn('{{%person}}','studio_id',$this->integer().' NULL DEFAULT NULL');
        $this->addForeignKey('fk_person_studio',$this->tableName,'studio_id','{{%studio}}','id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_person_studio',$this->tableName);
        $this->dropColumn('{{%person}}','studio_id');
    }
}
