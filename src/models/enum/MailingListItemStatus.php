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


    static public $list = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_UNSUBSCRIBED => 'Unsubscribed',
    ];

    public static $messageCategory = 'mailing';
}