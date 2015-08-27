<?php
use frontend\models\UserExtendsBride;
use frontend\models\UserExtendsBridesHistory;
use yii\db\Migration;

class m150827_110835_AddColumns_UserExtendsBride extends Migration
{

    /**
     *
     */
    public function up()
    {
        $this->addColumn(UserExtendsBride::tableName(), 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
        $this->addColumn(UserExtendsBridesHistory::tableName(), 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
    }

    public function down()
    {
        $this->dropColumn(UserExtendsBride::tableName(), 'date_birth');
        $this->dropColumn(UserExtendsBridesHistory::tableName(), 'date_birth');
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
