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
    'sending' => [
        'id' => 2,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_SENDING
    ],
    'ok' => [
        'id' => 3,
        'title' => 1,
        'content' => 1,
        'created' => 1,
        'updated' => 1,
        'status' => Mailing::STATUS_DRAFT
    ]
];