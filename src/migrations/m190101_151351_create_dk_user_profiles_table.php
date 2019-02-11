<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dk_shop_user_profiles`.
 */
class m190101_151351_create_dk_user_profiles_table extends Migration
{
    private $usersProfilesTable = '{{%dk_user_profiles}}';
    private $userTable = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->usersProfilesTable, [
            'user_id'     => $this->integer()->notNull(),
            'first_name'  => $this->string(45)->defaultValue(NULL),
            'last_name'   => $this->string(45)->defaultValue(NULL),
            'middle_name' => $this->string(45)->defaultValue(NULL),
        ], $tableOptions);
        $this->addPrimaryKey(
            'primary-key',
            $this->usersProfilesTable,
            'user_id'
        );
        $this->addForeignKey(
            'fk_dk_user_profiles_user_id',
            $this->usersProfilesTable,
            'user_id',
            $this->userTable,
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_dk_user_profiles_user_id', $this->usersProfilesTable);
        $this->dropTable($this->usersProfilesTable);
    }
}
