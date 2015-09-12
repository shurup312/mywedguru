<?php
use yii\db\Migration;

class m150830_090003_AddTable_Person extends Migration
{

    public $tableName = '{{%person}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer()->notNull().' COMMENT "Пользователь"',
            'first_name'   => $this->string(32).' NULL DEFAULT NULL COMMENT "Имя"',
            'last_name'    => $this->string(32).' NULL DEFAULT NULL COMMENT "Фамилия"',
            'sex'          => $this->integer().' NULL DEFAULT NULL COMMENT "Пол"',
            'date_birth'   => $this->timestamp().' NULL DEFAULT NULL COMMENT "Дата рождения"',
            'mob_phone'    => $this->string(32).' NULL DEFAULT NULL COMMENT "Мобильный телефон"',
            'phone'        => $this->string(32).' NULL DEFAULT NULL COMMENT "Домашний телефон"',
            'address'      => $this->text().' NULL DEFAULT NULL COMMENT "Адрес"',
            'email'        => $this->string(128).' NULL DEFAULT NULL COMMENT "E-mail"',
            'contact_id'   => $this->integer().' NULL DEFAULT NULL COMMENT "Контакты"',
            'about'        => $this->text().' NULL DEFAULT NULL COMMENT "Обо мне"',
            'date_created' => $this->timestamp()->notNull().' DEFAULT CURRENT_TIMESTAMP',
            'date_deleted' => $this->timestamp().' NULL DEFAULT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_person_user', $this->tableName, 'user_id', 'users', 'id', 'cascade', 'cascade');
        $this->dropColumn('users', 'person_id');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
        $this->addColumn('users', 'person_id', $this->integer());
    }
}
