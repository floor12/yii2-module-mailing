<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\logic\MailingView;
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
class MailingViewTest extends TestCase
{

    public function _before()
    {
        $fixtureMailing = new MailingFixture();
        $fixtureMailing->dataFile = __DIR__ . "/../_data/mailing.php";
        $fixtureMailing->load();
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

    /** Передаем несуществующий ID рассылки
     * @expectedException yii\web\NotFoundHttpException
     * @expectedExceptionMessage Newsletter not exists.
     * @throws \yii\base\InvalidConfigException
     */
    public function testWrongId()
    {
        $hash = md5(time());
        Yii::createObject(MailingView::class, [100000, $hash])->execute();
    }

    /** Проверям работоспособность
     * @throws \yii\base\InvalidConfigException
     */
    public function testCreateView()
    {
        $model = Mailing::findOne(1);
        $this->assertEquals(0, $model->views);

        $hash = md5(time());

        Yii::createObject(MailingView::class, [$model->id, $hash])->execute();
        $this->assertEquals(1, $model->views);
    }

    /** Проверяем чтобы просмотры не задублировались
     * @throws \yii\base\InvalidConfigException
     */
    public function testPreventDoubleView()
    {
        $model = Mailing::findOne(1);
        $this->assertEquals(0, $model->views);

        $hash = md5(time());

        $res =  Yii::createObject(MailingView::class, [$model->id, $hash])->execute();
        $this->assertTrue($res);
        $this->assertEquals(1, $model->views);

        $res = Yii::createObject(MailingView::class, [$model->id, $hash])->execute();
        $this->assertFalse($res);
        $this->assertEquals(1, $model->views);
    }

}