<?php

namespace floor12\mailing\tests;

use yii\db\Migration;

/**
 * Class m180717_120000_user
 */
class m180717_120000_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // MAILING
        $this->createTable("{{%user}}", [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull(),
            'user_email' => $this->string()->notNull(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%user}}");
    }
}