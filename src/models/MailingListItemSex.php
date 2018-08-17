<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.08.2018
 * Time: 14:38
 */

namespace floor12\mailing\models;


use yii2mod\enum\helpers\BaseEnum;

class MailingListItemSex extends BaseEnum
{
    const NONE = 0;
    const MAN = 1;
    const WOMAN = 2;

    public  static $list = [
        self::NONE => 'Неизвестен',
        self::MAN => 'Мужской',
        self::WOMAN => 'Женский',
    ];

}