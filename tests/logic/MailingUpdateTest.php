<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use \floor12\mailing\tests\MockedUser;
use floor12\mailing\logic\MailingUpdate;
use floor12\mailing\models\Mailing;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\fixtures\MailingEmailFixture;
use floor12\mailing\tests\TestCase;
use \Yii;

/**
 * @group mailing-update
 */
class MailingUpdateTest extends TestCase
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

    /** Проверяем создание, выставления временных меток и добавление адресов
     * @throws \yii\base\InvalidConfigException
     */
    public function testSuccess()
    {
        $model = new Mailing();
        $user = new MockedUser();
        $data = ['Mailing' => [
            'content' => 'content',
            'title' => 'title',
            'emails_array' => ['test44@test.ru', 'test55@test.ru']
        ]];

        Yii::createObject(MailingUpdate::class, [$model, $data, $user])->execute();

        $model->refresh();

        $this->assertEquals(2, sizeof($model->emails));
        $this->assertEquals('content', $model->content);
        $this->assertEquals('title', $model->title);
        $this->assertEquals(Mailing::STATUS_DRAFT, $model->status);
    }

//
//    /** Вызываем пуск рассылки, которая имеет получателей.
//     * @expectedException yii\web\BadRequestHttpException
//     * @expectedExceptionMessage У этой рассылки нет ни одного получателя.
//     */
//    public function testHasNoemail()
//    {
//        $model = Mailing::findOne(1);
//        Yii::createObject(MailingSend::class, [$model])->execute();
//    }
//
//
//    /**
//     * Проверяем нормальный вариант
//     */
//    public function testOk()
//    {
//        $model = Mailing::findOne(3);
//        $this->assertEquals(Mailing::STATUS_DRAFT, $model->status);
//        Yii::createObject(MailingSend::class, [$model])->execute();
//        $model->refresh();
//        $this->assertEquals(Mailing::STATUS_WAITING, $model->status);
//    }


}