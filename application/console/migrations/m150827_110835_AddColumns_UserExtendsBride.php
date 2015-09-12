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
        $this->addColumn('user_extends_brides', 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
        $this->addColumn('user_extends_brides_history', 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
    }

    public function down()
    {
        $this->dropColumn('user_extends_brides', 'date_birth');
        $this->dropColumn('user_extends_brides_history', 'date_birth');
    }
}
