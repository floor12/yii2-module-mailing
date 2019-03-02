<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.08.2018
 * Time: 14:38
 */

namespace floor12\mailing\models\enum;

use yii2mod\enum\helpers\BaseEnum;

class MailingListItemSex extends BaseEnum
{
    const NONE = 0;
    const MAN = 1;
    const WOMAN = 2;

    static public $list = [
        self::NONE => 'Unknown',
        self::MAN => 'Man',
        self::WOMAN => 'Woman',
    ];

    public static $messageCategory = 'app.f12.mailing';


}