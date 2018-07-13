<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.01.2018
 * Time: 12:45
 */

namespace floor12\mailing\tests\logic;

use floor12\mailing\tests\TestCase;

/**
 * Class FileReformatTest
 * @package floor12\files\tests\logic
 * @group reformat
 */
class FileReformatTest extends TestCase
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

    public function testAlreadySend()
    {
        $this->assertTrue(true);
    }


}