<?php
use yii\db\Schema;
use yii\db\Migration;

class m150830_084922_AlterTable_Users extends Migration
{

    public function up()
    {
        $this->dropColumn('users', 'rights');
        $this->renameColumn('users', 'user_type', 'type');
        $this->addColumn('users', 'image_id', $this->integer());
        $this->addColumn('users', 'person_id', $this->integer());
    }

    public function down()
    {
        $this->addColumn('users', 'rights', $this->integer());
        $this->renameColumn('users', 'type', 'user_type');
        $this->dropColumn('users', 'image_id');
        $this->dropColumn('users', 'person_id');
    }
}
