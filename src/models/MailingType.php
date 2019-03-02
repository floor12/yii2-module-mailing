<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.08.2018
 * Time: 18:31
 */

namespace floor12\mailing\models;

use Yii;
use yii2mod\enum\helpers\BaseEnum;

class MailingType extends BaseEnum
{
    const FREE = 0;
    const EXT_CLASS = 1;
    const LIST = 2;

    public static $list = [];

    public function __construct()
    {
        parent::__construct();
        self::$list = [
            self::FREE => Yii::t('mailing', 'Arbitrary address list'),
            self::EXT_CLASS => Yii::t('mailing', 'Objects of the outer class'),
            self::LIST => Yii::t('mailing', 'Saved address list'),
        ];
    }
}