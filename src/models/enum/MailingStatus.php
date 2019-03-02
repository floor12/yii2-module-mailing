<?php
/**
 * Created by PhpStorm.
 * User: KiViCMS
 * Date: 02.03.2019
 * Time: 15:14
 */

namespace floor12\mailing\models\enum;

use yii2mod\enum\helpers\BaseEnum;


class MailingStatus extends BaseEnum
{
    const STATUS_DRAFT = 0;
    const STATUS_WAITING = 1;
    const STATUS_SENDING = 2;
    const STATUS_SEND = 3;

    static public $list = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_WAITING => 'In the queue for sending',
        self::STATUS_SENDING => 'Sending',
        self::STATUS_SEND => 'Sent',
    ];

    public static $messageCategory = 'app.f12.mailing';
}