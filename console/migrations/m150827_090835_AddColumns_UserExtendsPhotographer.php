<?php
use frontend\models\UserExtendsPhotographer;
use frontend\models\UserExtendsPhotographersHistory;
use yii\db\Migration;

class m150827_090835_AddColumns_UserExtendsPhotographer extends Migration
{

    public function up()
    {
        $this->addColumn(UserExtendsPhotographer::tableName(), 'about', $this->text().' NULL DEFAULT NULL COMMENT "О себе"');
        $this->addColumn(UserExtendsPhotographer::tableName(), 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
        $this->addColumn(UserExtendsPhotographersHistory::tableName(), 'about', $this->text().' NULL DEFAULT NULL COMMENT "О себе"');
        $this->addColumn(UserExtendsPhotographersHistory::tableName(), 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
    }

    public function down()
    {
        $this->dropColumn(UserExtendsPhotographer::tableName(), 'about');
        $this->dropColumn(UserExtendsPhotographer::tableName(), 'date_birth');
        $this->dropColumn(UserExtendsPhotographersHistory::tableName(), 'about');
        $this->dropColumn(UserExtendsPhotographersHistory::tableName(), 'date_birth');
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
