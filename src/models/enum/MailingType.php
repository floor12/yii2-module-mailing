<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.08.2018
 * Time: 18:31
 */

namespace floor12\mailing\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class MailingType extends BaseEnum
{
    const FREE = 0;
    const EXT_CLASS = 1;
    const LIST = 2;

    static public $list = [
        self::FREE => 'Arbitrary address list',
        self::EXT_CLASS => 'Objects of the outer class',
        self::LIST => 'Saved address list',
    ];

    public static $messageCategory = 'mailing';

}