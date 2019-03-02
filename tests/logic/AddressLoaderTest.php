<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\logic\AddressLoader;
use floor12\mailing\models\enum\MailingListStatus;
use floor12\mailing\models\MailingList;
use floor12\mailing\tests\TestCase;

/**
 * @group mailing-loader
 */
class AddressLoaderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->setApp();
    }

    public function tearDown()
    {
        $this->clearDb();
        parent::tearDown();
    }


    public function testParseEmails()
    {
        $listId = 666;
        $string = 'test@test.ru, some@email.com;   test@test.ru new@emails.net';
        $list = new MailingList(['id' => $listId]);
        $logic = new AddressLoader($list, $string);
        $logic->parseAddresses();

        $parsedEmails = $logic->getAddresses();
        $this->assertEquals(4, sizeof($parsedEmails));
        $this->assertEquals('test@test.ru', $parsedEmails[0]);
        $this->assertEquals('some@email.com', $parsedEmails[1]);
        $this->assertEquals('test@test.ru', $parsedEmails[2]);
        $this->assertEquals('new@emails.net', $parsedEmails[3]);
    }

    public function testSaveEmails()
    {
        $string = 'test@test.ru, some@email.com;   test@test.ru new@emails.net';
        $list = new MailingList(['title' => 'testList', 'status' => MailingListStatus::STATUS_ACTIVE]);
        $this->assertTrue($list->save());
        $logic = new AddressLoader($list, $string);
        $this->assertTrue($logic->execute());

        $items = $list->listItems;
        $this->assertEquals(3, sizeof($items));
    }

}