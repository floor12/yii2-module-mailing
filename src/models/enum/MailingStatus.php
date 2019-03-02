<?php
/**
 * Created by PhpStorm.
 * User: KiViCMS
 * Date: 02.03.2019
 * Time: 15:14
 */

namespace floor12\mailing\models\enum;

use Yii;
use yii2mod\enum\helpers\BaseEnum;


class MailingStatus extends BaseEnum
{
    const STATUS_DRAFT = 0;
    const STATUS_WAITING = 1;
    const STATUS_SENDING = 2;
    const STATUS_SEND = 3;

    public $list = [];

    public function __construct()
    {
        parent::__construct();

        self::$list = [
            self::STATUS_DRAFT => Yii::t('Draft'),
            self::STATUS_WAITING => Yii::t('In the queue for sending'),
            self::STATUS_SENDING => Yii::t('Sending'),
            self::STATUS_SEND => Yii::t('Sent'),
        ];

    }
}