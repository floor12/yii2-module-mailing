<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 14.07.2018
 * Time: 20:19
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\MailingLink;
use \Yii;

class MailingLinkCreate
{
    private $_mailing_id;
    private $_link;
    private $_model;

    public function __construct(int $mailing_id, string $link)
    {
        $this->_link = $link;
        $this->_mailing_id = $mailing_id;
        $this->_model = new MailingLink();

    }

    /**
     * @return string
     * @throws \ErrorException
     */
    public function getRedirectLink()
    {
        $this->_model->link = $this->_link;
        $this->_model->mailing_id = $this->_mailing_id;
        $this->_model->hash = md5("{$this->_mailing_id}{$this->_link}" . time());

        if (!$this->_model->save())
            throw new \ErrorException(Yii::t('mailing', 'Could not generate link for redirect: {0}', $this->_link));

        return Yii::$app->getModule('mailing')->makeRedirectUrl($this->_model->hash);
    }


}