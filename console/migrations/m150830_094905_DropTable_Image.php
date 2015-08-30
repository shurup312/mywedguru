<?php
use yii\db\Schema;
use yii\db\Migration;

class m150830_094905_DropTable_Image extends Migration
{

    public function up()
    {
        $this->dropTable('images');
        $this->renameColumn('users', 'image_id', 'avatar');
    }

    public function down()
    {
        echo "m150830_094905_DropTable_Image cannot be reverted.\n";
        return false;
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
