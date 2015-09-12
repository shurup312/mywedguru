<?php

use yii\db\Schema;
use yii\db\Migration;

class m150912_153048_AddColumn_Person extends Migration
{

    private $tableName = '{{%studio}}';

    public function up()
    {
        $this->insert($this->tableName,['id'=>1, 'name'=>'Без студии']);
    }

    public function down()
    {
        $this->delete($this->tableName,['id'=>1]);
    }
}
