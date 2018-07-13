<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 11.07.2018
 * Time: 18:43
 */

namespace floor12\mailing\controllers;

use floor12\mailing\logic\MailingSend;
use floor12\mailing\logic\MailingUpdate;
use floor12\mailing\models\filters\MailingFilter;
use yii\web\Controller;
use yii\filters\AccessControl;
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingList;
use \Yii;

class MailingController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Yii::$app->getModule('mailing')->editRole],
                    ],
                ],
            ],
        ];
    }

//    public function actionMailview($hash, $id)
//    {
//        \Yii::createObject(MailViewed::class, [$id, $hash])->execute();
//        header('Content-Type: image/gif');
//        return readfile(\Yii::getAlias('@webroot/images') . '/1x1.gif');
//    }
//
    public function actionSend()
    {
        $model = Mailing::findOne((int)Yii::$app->request->post('id'));
        if (!$model)
            throw new NotFoundHttpException("Рассылка {$id} не найдена");

        Yii::createObject(MailingSend::class, [$model])->execute();
    }
//
//
//    public function actionIds($mode)
//    {
//        $query = Client::find()->select('user_id')->joinWith('bases');
//
//        if ($mode == 'active') {
//            $query->where(['=', 'bases.is_available', Base::STATUS_AVAILABLE]);
//        }
//
//        if ($mode == 'inactive') {
//            $query->where(['=', 'bases.is_available', Base::STATUS_DISABLED]);
//        }
//
//        return json_encode($query->column());
//    }
//
    public function actionIndex()
    {
        $model = new MailingFilter();
        $model->load(\Yii::$app->request->get());
        return $this->render('index', ['model' => $model]);
    }

    public function actions()
    {
        return [
            'form' => [
                'class' => \floor12\editmodal\EditModalAction::className(),
                'model' => Mailing::className(),
                'logic' => MailingUpdate::class,
                'viewParams' => ['lists' => MailingList::find()->forSelect()],
                'message' => 'Рассылка сохранена'
            ],
            'delete' => [
                'class' => \floor12\editmodal\DeleteAction::className(),
                'model' => Mailing::className(),
                'message' => 'Рассылка удалена'
            ],
        ];
    }


}