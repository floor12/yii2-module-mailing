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
use floor12\mailing\models\MailingExternal;
use Yii;
use yii\web\IdentityInterface;

class MailingUpdate
{
    private $_model;
    private $_data;
    private $_identity;
    private $_module;

    public function __construct(Mailing $model, array $data, IdentityInterface $identity)
    {
        $this->_module = Yii::$app->getModule('mailing');

        if (!is_array($data['Mailing']['emails_array']))
            $data['Mailing']['emails_array'] = [];

        $this->_data = $data;
        $this->_identity = $identity;
        $this->_model = $model;
        if ($this->_model->isNewRecord) {
            $this->_model->created = time();
            $this->_model->create_user_id = $this->_identity->getId();
            $this->_model->status = Mailing::STATUS_DRAFT;
        }
        $this->_model->updated = time();
        $this->_model->update_user_id = $this->_identity->getId();
    }

    public function execute()
    {
        $this->_model->load($this->_data);
        if ($this->_model->save()) {
            $this->linkEmails();
            if ($this->_module->linkedModels)
                $this->linkExternal();
            return true;
        }
        return false;
    }

    /**
     * Линкуем текущую рассылку и ее почтовые адреса
     * We link the current newsletter and its email addresses
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

    /**
     * Линкуем текущую рассылку с моделями проекта по которым тоже надо рассылать
     * We link the current newsletter with the project models for which we also need to send
     */
    private function linkExternal()
    {
        if ($this->_module->linkedModels)
            foreach ($this->_module->linkedModels as $linkedModelKey => $linkedModelClass) {
                MailingExternal::deleteAll(['class' => $linkedModelClass, 'mailing_id' => $this->_model->id]);
                if (!empty($this->_data['Mailing']['external_ids'][$linkedModelKey]))
                    foreach ($this->_data['Mailing']['external_ids'][$linkedModelKey] as $external_id) {
                        $externalModel = new MailingExternal();
                        $externalModel->mailing_id = $this->_model->id;
                        $externalModel->class = $linkedModelClass;
                        $externalModel->object_id = $external_id;
                        $externalModel->save();
                    }
            }
    }
}