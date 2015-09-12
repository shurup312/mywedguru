<?php
use yii\db\Schema;
use yii\db\Migration;

class m150906_093020_AlterTables_Person_Users extends Migration
{

    public function up()
    {
        $this->dropForeignKey('fk_person_user', '{{%person}}');
        $this->dropColumn('{{%person}}', 'user_id');
        $this->addColumn('{{%users}}', 'person_id', $this->integer().' NULL DEFAULT NULL COMMENT "ID персоны"');
        $this->addForeignKey('fk_user_person', '{{%users}}', 'person_id', '{{%person}}', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_person', '{{%users}}');
        $this->addColumn('{{%person}}', 'user_id', $this->integer()->notNull().' COMMENT "ID пользователя"');
        $this->dropColumn('{{%users}}', 'person_id');
        $this->addForeignKey('fk_person_user', '{{%person}}', 'user_id', '{{%users}}', 'id', 'cascade', 'cascade');
    }
}
