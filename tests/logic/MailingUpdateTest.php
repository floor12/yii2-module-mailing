<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\logic\MailingUpdate;
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingEmail;
use floor12\mailing\tests\fixtures\MailingEmailFixture;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\fixtures\UserFixture;
use floor12\mailing\tests\TestCase;
use floor12\mailing\tests\User;
use Yii;

/**
 * @group mailing-update
 */
class MailingUpdateTest extends TestCase
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


        $fixtureUser = new UserFixture();
        $fixtureUser->dataFile = __DIR__ . "/../_data/user.php";
        $fixtureUser->load();
    }

    public function tearDown()
    {
        $this->_after();
        // $this->clearDb();
        parent::tearDown();

    }

    public function _after()
    {
        Mailing::deleteAll();
        MailingEmail::deleteAll();
        User::deleteAll();
    }

    /** Проверяем создание, выставления временных меток и добавление адресов
     * @throws \yii\base\InvalidConfigException
     */
    public function testSuccessCreate()
    {
        $model = new Mailing();
        $user = new User();
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
        $this->assertEquals($user->getId(), $model->create_user_id);
        $this->assertEquals($user->getId(), $model->update_user_id);
        $this->assertEquals(Mailing::STATUS_DRAFT, $model->status);
    }

    /** Проверяем создание, выставления временных меток и добавление адресов
     * @throws \yii\base\InvalidConfigException
     */
    public function testSuccessUpdate()
    {
        $model = Mailing::findOne(3);
        $user = new User();
        $data = ['Mailing' => [
            'content' => 'content',
            'title' => 'title',
            'emails_array' => ['test44@test.ru', 'test55@test.ru']
        ]];

        $this->assertEquals(3, sizeof($model->recipients));
    }

    /** Проверяем создание, выставления временных меток и добавление адресов
     * @throws \yii\base\InvalidConfigException
     * @group mailing-update-ext
     */
    public function testSuccessUpdateWithExternalModels()
    {
        $model = Mailing::findOne(3);
        $user = new User();
        $data = ['Mailing' => [
            'content' => 'content',
            'title' => 'title',
            'emails_array' => [],
            'external_ids' => [['1']]
        ]];

        Yii::createObject(MailingUpdate::class, [$model, $data, $user])->execute();
        $model->refresh();
        $this->assertEquals('valera@test.ru', $model->recipients[0]);
        $this->assertEquals(1, sizeof($model->recipients));
    }
}