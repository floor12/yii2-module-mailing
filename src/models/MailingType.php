<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.08.2018
 * Time: 18:31
 */

namespace floor12\mailing\models;


use yii2mod\enum\helpers\BaseEnum;

class MailingType extends BaseEnum
{
    const FREE = 0;
    const EXT_CLASS = 1;
    const LIST = 2;

    public static $list = [
      self::FREE => 'Произвольный список адресов',
      self::EXT_CLASS => 'Объекты внешнего класса ',
      self::LIST => 'Сохраненный писок адресов',
    ];
}