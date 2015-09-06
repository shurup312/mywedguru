<?php

use yii\db\Schema;
use yii\db\Migration;

class m150906_101947_AlterTable_Person extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%person}}','contact_id');
    }

    public function down()
    {
        $this->addColumn('{{%person}}','contact_id', $this->integer());
    }
}
