<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 15.07.2018
 * Time: 16:43
 */

namespace floor12\mailing\controllers;

use floor12\mailing\logic\MailingClick;
use floor12\mailing\logic\MailingView;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;

class StatController extends Controller
{

    public function init()
    {
        $this->layout = Yii::$app->getModule('mailing')->layoutFrontend;
        parent::init();
    }

    public function actionLink($hash)
    {
        if (Yii::$app->request->method == 'HEAD')
            return false;
        $url = Yii::createObject(MailingClick::class, [$hash])->execute();
        $this->redirect($url);
    }


    public function actionGif($id, $hash)
    {
        Yii::createObject(MailingView::class, [$id, $hash])->execute();
        header('Content-Type: image/gif');
        readfile(\Yii::getAlias('@vendor/floor12/yii2-module-mailing/src/assets/1x1.gif'));
    }

    public function actionUnsubscribe()
    {
        // Yii::createObject(MailingUnsubscribe::class, [$email, $list_id, $hash])->execute();
        return $this->renderContent(Html::tag('h1', Yii::t('mailing', 'You have been successfully unsubscribed.'), ['class' => 'f12-mailing-unsubscribe-success']));
    }
}