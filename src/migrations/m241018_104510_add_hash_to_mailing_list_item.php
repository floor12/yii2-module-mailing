<?php

use yii\db\Migration;

/**
 * Class m241018_104510_add_hash_to_mailing_list_item
 */
class m241018_104510_add_hash_to_mailing_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->addColumn('mailing_list_item', 'hash', $this->string(32)->null()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropColumn('mailing_list_item', 'hash');
    }
}