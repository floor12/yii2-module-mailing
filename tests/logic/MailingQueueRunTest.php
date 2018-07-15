<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\files\m180627_121715_files;
use floor12\mailing\logic\MailingQueueRun;
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingEmail;
use floor12\mailing\tests\fixtures\MailingEmailFixture;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\TestCase;
use yii\base\ErrorException;

/**
 * @group mailing-run
 */
class MailingQueueRunTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->setApp();
        $this->_before();
    }

    public function _before()
    {
        $fixtureMailing = new MailingFixture();
        $fixtureMailing->dataFile = __DIR__ . "/../_data/mailing.php";
        $fixtureMailing->load();

        $fixtureMailingEmail = new MailingEmailFixture();
        $fixtureMailingEmail->dataFile = __DIR__ . "/../_data/mailingEmail.php";
        $fixtureMailingEmail->load();
    }

    public function tearDown()
    {
        $this->_after();
        $this->clearDb();
        parent::tearDown();

    }

    public function _after()
    {
        Mailing::deleteAll();
        MailingEmail::deleteAll();
    }

    private function clearEmails()
    {
        //dummy
    }

    /** Проверяем ошибку в случае, если очередь занята.
     * @expectedException ErrorException
     * @expectedExceptionMessage Очередь отправки занята.
     */
    public function testMailingQueueIsBusy()
    {
        $model = Mailing::findOne(4);
        $model->status = Mailing::STATUS_SENDING;
        $model->save();
        new MailingQueueRun();
    }

    /** Проверяем ответ если очередь пуста */
    public function testMailingQueueIsEmpty()
    {
        $logic = new MailingQueueRun();
        $this->assertEquals('Очередь пуста.', $logic->execute());
    }

    /** Проверяем ответ рассылка в очередь не имеет получателей (например очистили список рассылки) */
    public function testMailingHasNotRecipients()
    {
        $model = Mailing::findOne(1);
        $model->status = Mailing::STATUS_WAITING;
        $model->save();
        $logic = new MailingQueueRun();
        $this->assertEquals('Список получателей рассылки id:1 пуст.', $logic->execute());
    }

    public function testMailingQueueuRun()
    {
        $model = Mailing::findOne(4);
        $model->status = Mailing::STATUS_WAITING;
        $model->save();
        $this->assertEquals(0, sizeof($model->links));
        $logic = new MailingQueueRun();
        $res = $logic->execute();
        $this->assertEquals("success: 2", $res);
        $model->refresh();
        $this->assertEquals(2, sizeof($model->links));

    }



}