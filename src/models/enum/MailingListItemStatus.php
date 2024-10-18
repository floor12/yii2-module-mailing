<?php
/**
 * Created by PhpStorm.
 * User: KiViCMS
 * Date: 02.03.2019
 * Time: 15:14
 */

namespace floor12\mailing\models\enum;

use yii2mod\enum\helpers\BaseEnum;


class MailingListItemStatus extends BaseEnum
{
    const STATUS_ACTIVE = 0;
    const STATUS_UNSUBSCRIBED = 1;
    const STATUS_NOT_CONFIRMED = 2;


    static public $list = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_UNSUBSCRIBED => 'Unsubscribed',
        self::STATUS_NOT_CONFIRMED => 'Not confirmed',
    ];

    public static $messageCategory = 'app.f12.mailing';
}