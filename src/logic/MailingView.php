<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 23.12.2017
 * Time: 20:00
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingViewed;
use yii\web\NotFoundHttpException;

class MailingView
{
    private $_id, $_hash;

    public function __construct($id, $hash)
    {
        if (!Mailing::findOne((int)$id))
            throw new NotFoundHttpException(Yii::t('mailing', 'Newsletter not exists'));
        $this->_id = $id;
        $this->_hash = $hash;
    }

    public function execute()
    {
        $view = new MailingViewed();
        $view->mailing_id = $this->_id;
        $view->hash = $this->_hash;
        return $view->save();
    }
}