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
use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingList;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MailingController extends Controller
{

    public function init()
    {
        $this->layout = Yii::$app->getModule('mailing')->layoutBackend;
        parent::init();
    }

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

    public function actionSend()
    {
        $id = (int)Yii::$app->request->post('id');
        $model = Mailing::findOne($id);
        if (!$model)
            throw new NotFoundHttpException(Yii::t('mailing', 'Newsletter {0} not found', $id));

        Yii::createObject(MailingSend::class, [$model])->execute();
    }

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
                'viewParams' => [
                    'lists' => MailingList::find()->forSelect(),
                    'module' => Yii::$app->getModule('mailing')
                ],
                'message' => Yii::t('mailing', 'Newsletter saved')
            ],
            'delete' => [
                'class' => \floor12\editmodal\DeleteAction::className(),
                'model' => Mailing::className(),
                'message' => Yii::t('Newsletter deleted')
            ],
        ];
    }


}