<?php
use frontend\models\UserExtendsPhotographer;
use frontend\models\UserExtendsPhotographersHistory;
use yii\db\Migration;

class m150827_090835_AddColumns_UserExtendsPhotographer extends Migration
{

    public function safeUp()
    {
        $this->addColumn('user_extends_photographers', 'about', $this->text().' NULL DEFAULT NULL COMMENT "О себе"');
        $this->addColumn('user_extends_photographers', 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
        $this->addColumn('user_extends_photographers_history', 'about', $this->text().' NULL DEFAULT NULL COMMENT "О себе"');
        $this->addColumn('user_extends_photographers_history', 'date_birth', $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"');
    }

    public function safeDown()
    {
        $this->dropColumn('user_extends_photographers', 'about');
        $this->dropColumn('user_extends_photographers', 'date_birth');
        $this->dropColumn('user_extends_photographers_history', 'about');
        $this->dropColumn('user_extends_photographers_history', 'date_birth');
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
