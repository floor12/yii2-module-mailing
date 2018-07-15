<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\logic\MailingClick;
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingLink;
use floor12\mailing\models\MailingStat;
use floor12\mailing\tests\fixtures\MailingFixture;
use floor12\mailing\tests\fixtures\MailingLinkFixture;
use floor12\mailing\tests\TestCase;
use Yii;

/**
 * @group mailing-click
 */
class MailingClickTestTest extends TestCase
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

        $fixtureMailingLink = new MailingLinkFixture();
        $fixtureMailingLink->dataFile = __DIR__ . "/../_data/mailingLink.php";
        $fixtureMailingLink->load();
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
        MailingLink::deleteAll();
        MailingStat::deleteAll();
    }

    /** Передаем несуществующий ID рассылки
     * @expectedException yii\web\NotFoundHttpException
     * @expectedExceptionMessage Ссылка не найдена.
     * @throws \yii\base\InvalidConfigException
     */

    public function testWrongHash()
    {
        $hash = 'WORNGHASH';
        Yii::createObject(MailingClick::class, [$hash])->execute();
    }

    /** Посылаем реальных хеш и проверяем, что увеличился счетчик
     * @throws \yii\base\InvalidConfigException
     */
    public function testSuccessHash()
    {
        $hash = '10THIS40IS29HASH';
        $mailing = Mailing::findOne(5);
        $this->assertEquals(0, $mailing->clicks);
        $url = Yii::createObject(MailingClick::class, [$hash])->execute();
        $mailing->refresh();
        $this->assertEquals(1, $mailing->clicks);
        $this->assertEquals('http://this-is-test-link.ru', $url);
    }


}