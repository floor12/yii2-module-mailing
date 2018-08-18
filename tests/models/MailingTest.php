<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\models;

use floor12\mailing\models\MailingEmail;
use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingListItem;
use floor12\mailing\tests\fixtures\MailingExternalFixture;
use floor12\mailing\tests\fixtures\MailingListFixture;
use floor12\mailing\tests\fixtures\MailingListItemFixture;
use floor12\mailing\models\Mailing;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\fixtures\MailingEmailFixture;
use floor12\mailing\tests\fixtures\UserFixture;
use floor12\mailing\tests\TestCase;
use \Yii;
use floor12\mailing\tests\User;

/**
 * @group mailing
 */
class MailingTest extends TestCase
{

    public function _before()
    {
        $fixtureMailing = new MailingFixture();
        $fixtureMailing->dataFile = __DIR__ . "/../_data/mailing.php";
        $fixtureMailing->load();

        $fixtureMailingEmail = new MailingEmailFixture();
        $fixtureMailingEmail->dataFile = __DIR__ . "/../_data/mailingEmail.php";
        $fixtureMailingEmail->load();

        $fixtureMailingList = new MailingListFixture();
        $fixtureMailingList->dataFile = __DIR__ . "/../_data/mailingList.php";
        $fixtureMailingList->load();

        $fixtureMailingListItem = new MailingListItemFixture();
        $fixtureMailingListItem->dataFile = __DIR__ . "/../_data/mailingListItem.php";
        $fixtureMailingListItem->load();

        $fixtureMailingExternal = new MailingExternalFixture();
        $fixtureMailingExternal->dataFile = __DIR__ . "/../_data/mailingExternal.php";
        $fixtureMailingExternal->load();

        $fixtureUser = new UserFixture();
        $fixtureUser->dataFile = __DIR__ . "/../_data/user.php";
        $fixtureUser->load();
    }

    public function _after()
    {
        Mailing::deleteAll();
        MailingEmail::deleteAll();
        MailingList::deleteAll();
        MailingListItem::deleteAll();
        User::deleteAll();
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

    /** Проверяем как из всех источников почтовые адреса собираются в один массив
     *
     */
    public function testCheckRecipients1()
    {
        $model = Mailing::findOne(3);
        $this->assertEquals(sizeof($model->recipients), 3);
        $this->assertEquals($model->recipient_total, 3);
    }

    /**
     * Проверяем как из всех источников почтовые адреса собираются в один массив
     */
    public function testCheckRecipients2()
    {
        $model = Mailing::findOne(4);
        $this->assertEquals(sizeof($model->recipients), 2);
        $this->assertEquals($model->recipient_total, 2);
    }

    /**
     * Проверяем как заполняется временное поле $emails_array
     */
    public function testCheckEmailsArray()
    {
        $model = Mailing::findOne(3);
        $this->assertEquals(3, sizeof($model->emails));
        $this->assertEquals(3, sizeof($model->emails_array));
        $this->assertEquals($model->emails[0]->email, $model->emails_array[$model->emails[0]->email]);
        $this->assertEquals($model->emails[1]->email, $model->emails_array[$model->emails[1]->email]);
        $this->assertEquals($model->emails[2]->email, $model->emails_array[$model->emails[2]->email]);
    }


}