<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 21:39
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\enum\MailingStatus;
use floor12\mailing\models\Mailing;
use Yii;
use yii\web\BadRequestHttpException;

class MailingSend
{
    private $_model;

    public function __construct(Mailing $model)
    {
        $this->_model = $model;
        if ($this->_model->status != MailingStatus::STATUS_DRAFT)
            throw new BadRequestHttpException(Yii::t('app.f12.mailing', 'This newsletter is not in draft status.'));

        if (!$model->recipient_total)
            throw new BadRequestHttpException(Yii::t('app.f12.mailing', 'This mailing has no recipients.'));
    }

    public function execute()
    {
        $this->_model->send = time();
        $this->_model->status = MailingStatus::STATUS_WAITING;
        return $this->_model->save(true, ['status', 'send']);
    }

}