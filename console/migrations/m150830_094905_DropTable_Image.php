<?php
use yii\db\Schema;
use yii\db\Migration;

class m150830_094905_DropTable_Image extends Migration
{

    private $tableName = "{{%images}}";

    public function up()
    {
        $this->dropTable('images');
        $this->renameColumn('users', 'image_id', 'avatar');
    }

    public function down()
    {
        $this->renameColumn('users', 'avatar', 'image_id');
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer(),
            'path'         => $this->string()->notNull(),
            'filename'     => $this->string()->notNull(),
            'date_created' => $this->timestamp(),
            'date_deleted' => $this->timestamp(),
        ], $tableOptions);
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
