<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 21:39
 */

namespace floor12\mailing\logic;


use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingEmail;
use yii\web\IdentityInterface;

class MailingUpdate
{
    private $_model;
    private $_data;
    private $_identity;

    public function __construct(Mailing $model, array $data, IdentityInterface $identity)
    {
        $this->_data = $data;
        $this->_identity = $identity;
        $this->_model = $model;
    }

    public function execute()
    {
        $this->_model->load($this->_data);
        if ($this->_model->save()) {
            $this->linkEmails();
            return true;
        }
        return false;
    }

    /**
     * Линкуем текущую рассылку и ее почтовые адреса
     */
    private function linkEmails()
    {
        MailingEmail::deleteAll(['mailing_id' => $this->_model->id]);
        if ($this->_model->emails_array)
            foreach ($this->_model->emails_array as $email) {
                $emailObject = new MailingEmail();
                $emailObject->mailing_id = $this->_model->id;
                $emailObject->email = $email;
                $emailObject->save();
            }
    }
}