<?php

use yii\db\Migration;

/**
 * Class m180712_083434_mailing
 */
class m180712_083434_mailing extends Migration
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
        $this->createTable("{{%mailing}}", [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull()->comment('Текущее состояние'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'content' => $this->text()->notNull()->comment('Содержание'),
            'created' => $this->integer()->notNull()->comment('Создана'),
            'updated' => $this->integer()->notNull()->comment('Обновлена'),
            'send' => $this->integer()->null()->comment('Отправлена'),
            'list_id' => $this->integer()->null()->comment('Список для рассылки'),
            'create_user_id' => $this->integer()->null()->comment('Создал'),
            'update_user_id' => $this->integer()->null()->comment('Обновил'),
        ], $tableOptions);

        $this->createIndex("idx-mailing-status", "{{%mailing}}", "status");
        $this->createIndex("idx-mailing-send", "{{%mailing}}", "send");


        // MAILING VIEWED
        $this->createTable("{{%mailing_viewed}}", [
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'hash' => $this->string(255)->notNull()->comment('Уникальный хеш для статистики')
        ], $tableOptions);

        $this->createIndex("idx-mailing_viewed", "{{%mailing_viewed}}", ["mailing_id", "hash"], true);

        $this->addForeignKey("fk-mailing_viewed", "{{%mailing_viewed}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");

        // MAILING USERS
        $this->createTable("{{%mailing_user}}", [
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'user_id' => $this->integer()->notNull()->comment('Связь с пользователем'),
        ], $tableOptions);

        $this->createIndex("idx-mailing_user", "{{%mailing_user}}", ["mailing_id", "user_id"], true);

        $this->addForeignKey("fk-mailing_user", "{{%mailing_user}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");


        // MAILING EMAILS
        $this->createTable("{{%mailing_email}}", [
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'email' => $this->string()->notNull()->comment('Email'),
        ], $tableOptions);

        $this->createIndex("idx-mailing_email", "{{%mailing_email}}", ["mailing_id", "email"], true);

        $this->addForeignKey("fk-mailing_email", "{{%mailing_email}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");


        // MAILING EMAIL LIST
        $this->createTable("{{%mailing_list}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название списка'),
            'status' => $this->integer()->notNull()->comment('Скрыть')
        ], $tableOptions);

        $this->createIndex("idx-mailing_list-status", "{{%mailing_list}}", "status");

        // MAILING EMAIL LIST ITEM
        $this->createTable("{{%mailing_list_item}}", [
            'id' => $this->primaryKey(),
            'list_id' => $this->integer()->notNull()->comment('Связь со списком'),
            'email' => $this->string()->notNull()->comment('Email'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус')
        ], $tableOptions);

        $this->createIndex("idx-mailing_list_item-list_id", "{{%mailing_list_item}}", "list_id");
        $this->createIndex("idx-mailing_list_item-email", "{{%mailing_list_item}}", "email");
        $this->createIndex("idx-mailing_list_item-status", "{{%mailing_list_item}}", "status");

        $this->addForeignKey("fk-mailing_list_item-list_id", "{{%mailing_list_item}}", "list_id", "{{%mailing_list}}", "id", "CASCADE", "CASCADE");


        // MAILING LINK
        $this->createTable("{{%mailing_link}}", [
            'id' => $this->primaryKey(),
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'link' => $this->string()->notNull()->comment('Тело ссылки'),
            'hash' => $this->string()->notNull()->comment('Hash'),
        ], $tableOptions);

        $this->createIndex("idx-mailing_link-mailing_id", "{{%mailing_link}}", "mailing_id");
        $this->createIndex("idx-mailing_link-link", "{{%mailing_link}}", "link");
        $this->createIndex("idx-mailing_link-hash", "{{%mailing_link}}", "hash");
 
        $this->addForeignKey("fk-mailing_link", "{{%mailing_link}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");

        // MAILING STAT
        $this->createTable("{{%mailing_stat}}", [
            'id' => $this->primaryKey(),
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'link_id' => $this->integer()->notNull()->comment('Связь c ссылкой'),
            'timestamp' => $this->integer()->notNull()->comment('Временная метка'),
        ], $tableOptions);

        $this->createIndex("idx-mailing_link-mailing_stat", "{{%mailing_stat}}", "mailing_id");
        $this->createIndex("idx-mailing_link-link_id", "{{%mailing_stat}}", "link_id");
        $this->createIndex("idx-mailing_link-timestamp", "{{%mailing_stat}}", "timestamp");

        $this->addForeignKey("fk-mailing_stat-mailing", "{{%mailing_stat}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("fk-mailing_stat-link", "{{%mailing_stat}}", "link_id", "{{%mailing_link}}", "id", "CASCADE", "CASCADE");


        // MAILING EXTERNAL CLASSES OBJECT LINK
        $this->createTable("{{%mailing_external}}", [
            'mailing_id' => $this->integer()->notNull()->comment('Связь с рассылкой'),
            'class' => $this->string()->notNull()->comment('Полное имя класса связанного объекта'),
            'object_id' => $this->integer()->notNull()->comment('ID связанного объекта')
        ], $tableOptions);

        $this->createIndex('idx-mailing_external', "{{%mailing_external}}", ['mailing_id', 'class', 'object_id']);
        $this->createIndex('idx-mailing_external-mailing_id', "{{%mailing_external}}", 'mailing_id');
        $this->addForeignKey("fk-mailing_external-mailing", "{{%mailing_external}}", "mailing_id", "{{%mailing}}", "id", "CASCADE", "CASCADE");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%mailing_external}}");
        $this->dropTable("{{%mailing_stat}}");
        $this->dropTable("{{%mailing_link}}");
        $this->dropTable("{{%mailing_list_item}}");
        $this->dropTable("{{%mailing_list}}");
        $this->dropTable("{{%mailing_email}}");
        $this->dropTable("{{%mailing_user}}");
        $this->dropTable("{{%mailing_viewed}}");
        $this->dropTable("{{%mailing}}");
    }
}
