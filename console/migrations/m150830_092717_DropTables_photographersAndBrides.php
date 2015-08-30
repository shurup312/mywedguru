<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_092717_DropTables_photographersAndBrides extends Migration
{
    public function up()
    {
        $this->dropTable('user_extends_brides_history');
        $this->dropTable('user_extends_brides');
        $this->dropTable('user_extends_photographers_history');
        $this->dropTable('user_extends_photographers');
        $this->dropTable('user');
    }

    public function down()
    {
        echo "m150830_092717_DropTables_photographersAndBrides cannot be reverted.\n";

        return true;
    }
}
