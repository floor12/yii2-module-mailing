<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:40
 */

namespace floor12\mailing\tests;

use Yii;
use yii\console\Application;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     *  Запускаем приложение
     */
    protected function mockApplication()
    {
        new Application([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'runtimePath' => __DIR__ . '/runtime',
        ]);
    }

    /**
     * Настраиваем основные параметры приложения: базу данных и модуль
     */

    protected function setApp()
    {
        $files = [
            'class' => 'floor12\files\Module',
            'storage' => '@app/storage',
        ];
        \Yii::$app->setModule('files', $files);


        $mailingModule = [
            'class' => 'floor12\mailing\Module',
            'fromEmail' => 'test@example.com',
            'fromName' => 'Служба рассылки',
            'htmlTemplate' => '@vendor/../src/views/mailing-main.php',
            'layout' => 'test',
            'linkedModels' => [
                User::class
            ]

        ];

        \Yii::$app->setModule('mailing', $mailingModule);

        $db = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=mailing-test',
            'username' => 'travis',
            'password' => '',
            'charset' => 'utf8',
        ];

        Yii::$app->set('db', $db);

        $mailer = [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ];

        Yii::$app->set('mailer', $mailer);

        Yii::createObject(m180712_083434_mailing::class, [])->safeUp();
        Yii::createObject(m180717_120000_user::class, [])->safeUp();
        Yii::createObject(m180817_144300_add_fullname::class, [])->safeUp();

        Yii::$app->getModule('mailing')->init();
    }

    /**
     * Чистим за собой временную базу данных
     */
    protected function clearDb()
    {
        Yii::createObject(m180817_144300_add_fullname::class, [])->safeDown();
        Yii::createObject(m180712_083434_mailing::class, [])->safeDown();
        Yii::createObject(m180717_120000_user::class, [])->safeDown();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->destroyApplication();
        parent::tearDown();
    }

    /**
     * Убиваем приложение
     */
    protected function destroyApplication()
    {
        \Yii::$app = null;
    }
}
