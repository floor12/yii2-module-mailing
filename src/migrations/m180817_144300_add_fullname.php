<?php

use yii\db\Migration;

/**
 * Class m180817_add_fullname
 */
class m180817_144300_add_fullname extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%mailing_list_item}}", "fullname", $this->string()->null()->comment('Имя получателя'));
        $this->addColumn("{{%mailing_list_item}}", "sex", $this->tinyInteger()->defaultValue(0)->null()->comment('Пол получателя'));
        $this->addColumn("{{%mailing}}", "type", $this->tinyInteger()->defaultValue(0)->null()->comment('Тип рассылки'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%mailing_list_item}}", "fullname");
        $this->dropColumn("{{%mailing_list_item}}", "sex");
        $this->dropColumn("{{%mailing}}", "type");

    }
}