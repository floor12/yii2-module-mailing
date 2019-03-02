<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 23.12.2017
 * Time: 20:00
 */

namespace floor12\mailing\logic;

use Yii;
use floor12\mailing\models\MailingLink;
use floor12\mailing\models\MailingStat;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;


class MailingClick
{
    private $_link;
    private $_stat;

    public function __construct(string $hash)
    {
        $this->_link = MailingLink::findOne(['hash' => $hash]);
        if (!$this->_link)
            throw new NotFoundHttpException(Yii::t('mailing', 'Link not found.'));

        $this->_stat = new MailingStat();
    }

    public function execute()
    {
        $this->_stat->mailing_id = $this->_link->mailing_id;
        $this->_stat->link_id = $this->_link->id;
        $this->_stat->timestamp = time();
        if (!$this->_stat->save())
            throw new ErrorException(Yii::t('mailing', 'An error occurred while saving the statistics.'));
        return $this->_link->link;
    }
}