<?php
use yii\db\Schema;
use yii\db\Migration;

class m151004_132103_AddColumn_Users extends Migration
{

    const TABLE = '{{%users}}';
    const FIELD = 'slug';

    public function up()
    {
        $this->addColumn(self::TABLE, self::FIELD, $this->string(128).' NULL DEFAULT NULL COMMENT "Человекопонятный URL"');
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, self::FIELD);
    }
}
