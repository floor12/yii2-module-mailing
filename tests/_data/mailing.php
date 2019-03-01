<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.07.2018
 * Time: 18:42
 */

use floor12\mailing\models\Mailing;

return [
    'Ok but has not emails' => [
        'id' => 1,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_DRAFT
    ],
    'send' => [
        'id' => 2,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_SEND
    ],
    'ok' => [
        'id' => 3,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_DRAFT,
        'create_user_id' => 10,
        'update_user_id' => 10,
    ],
    'ok with list' => [
        'id' => 4,
        'title' => 1,
        'content' => "
            This is content with links.
            <a href=\"https://www.yiiframework.com/doc/guide/2.0/en/tutorial-core-validators#in\">Link #1</a>
            <p>
                This is <a href=\"http://test.me\" class='btn'>link #2</a>
            </p>
            <p>
                This is <a href='http://test.me' class='btn'>ignored</a>
            </p>
        ",
        'created' => 1,
        'updated' => 1,
        'list_id' => 1,
        'status' => Mailing::STATUS_DRAFT,
        'create_user_id' => 10,
        'update_user_id' => 10,
    ],
    'forClickTest' => [
        'id' => 5,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_SEND,
        'create_user_id' => 10,
        'update_user_id' => 10,
    ],
];