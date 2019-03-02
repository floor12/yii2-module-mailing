<?php
/**
 * Created by PhpStorm.
 * User: KiViCMS
 * Date: 02.03.2019
 * Time: 15:14
 */

namespace floor12\mailing\models\enum;

use yii2mod\enum\helpers\BaseEnum;


class MailingListStatus extends BaseEnum
{
    const STATUS_ACTIVE = 0;
    const STATUS_HIDE = 1;


    static public $list = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_HIDE => 'Hidden',
    ];

    public static     public static $messageCategory = 'app.f12.mailing';
= 'mailing';
}