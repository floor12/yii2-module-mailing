<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\logic\MailingSend;
use floor12\mailing\models\enum\MailingStatus;
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingEmail;
use floor12\mailing\tests\fixtures\MailingEmailFixture;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\TestCase;
use Yii;

/**
 * @group mailing-test
 */
class MailingSendTest extends TestCase
{

    public function _before()
    {
        $fixtureMailing = new MailingFixture();
        $fixtureMailing->dataFile = __DIR__ . "/../_data/mailing.php";
        $fixtureMailing->load();

        $fixtureMailingEmail = new MailingEmailFixture();
        $fixtureMailingEmail->dataFile = __DIR__ . "/../_data/mailingEmail.php";
        $fixtureMailingEmail->load();
    }

    public function _after()
    {
        Mailing::deleteAll();
        MailingEmail::deleteAll();
    }


    public function setUp()
    {
        parent::setUp();
        $this->setApp();
        $this->_before();
    }

    public function tearDown()
    {
        $this->_after();
        $this->clearDb();
        parent::tearDown();

    }

    /** Вызываем пуск рассылки, которая не в статусе черновика
     * @expectedException yii\web\BadRequestHttpException
     * @expectedExceptionMessage This newsletter is not in draft status.
     */
    public function testNotDraft()
    {
        $model = Mailing::findOne(2);
        Yii::createObject(MailingSend::class, [$model])->execute();
    }


    /** Вызываем пуск рассылки, которая имеет получателей.
     * @expectedException yii\web\BadRequestHttpException
     * @expectedExceptionMessage This mailing has no recipients.
     */
    public function testHasNoEmail()
    {
        $model = Mailing::findOne(1);
        Yii::createObject(MailingSend::class, [$model])->execute();
    }


    /**
     * Проверяем нормальный вариант
     */
    public function testOk()
    {
        $model = Mailing::findOne(3);
        $this->assertEquals(MailingStatus::STATUS_DRAFT, $model->status);
        Yii::createObject(MailingSend::class, [$model])->execute();
        $model->refresh();
        $this->assertEquals(MailingStatus::STATUS_WAITING, $model->status);
    }


}