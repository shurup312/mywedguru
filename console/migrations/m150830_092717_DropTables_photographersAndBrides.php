<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_092717_DropTables_photographersAndBrides extends Migration
{
    public function up()
    {
        $this->dropTable(\frontend\models\UserExtendsPhotographer::tableName());
        $this->dropTable(\frontend\models\UserExtendsPhotographersHistory::tableName());
        $this->dropTable(\frontend\models\UserExtendsBride::tableName());
        $this->dropTable(\frontend\models\UserExtendsBridesHistory::tableName());
        $this->dropTable('user');
    }

    public function down()
    {
        echo "m150830_092717_DropTables_photographersAndBrides cannot be reverted.\n";

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
