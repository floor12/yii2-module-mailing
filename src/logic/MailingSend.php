<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 21:39
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\Mailing;
use yii\web\BadRequestHttpException;

class MailingSend
{
    private $_model;

    public function __construct(Mailing $model)
    {
        $this->_model = $model;
        if ($this->_model->status != Mailing::STATUS_DRAFT)
            throw new BadRequestHttpException('Эта рассылка не находится в статусе черновика.');
    }

    public function execute()
    {
        $this->_model->send = time();
        $this->_model->status = Mailing::STATUS_WAITING;
        return $this->_model->save(true, ['status', 'send']);
    }

}