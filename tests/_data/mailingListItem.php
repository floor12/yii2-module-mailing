<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.07.2018
 * Time: 18:42
 */

use floor12\mailing\models\MailingListItem;

return [
    [
        'id' => 1,
        'list_id' => 1,
        'email' => 'not@unuquie.com',
        'status' => MailingListItem::STATUS_ACTIVE
    ],
    [
        'id' => 2,
        'list_id' => 1,
        'email' => 'unsubscribed@unuquie.com',
        'status' => MailingListItem::STATUS_UNSUBSCRIBED
    ],
    [
        'id' => 3,
        'list_id' => 1,
        'email' => 'test@gdfgdfgf.ru',
        'status' => MailingListItem::STATUS_ACTIVE
    ],

];