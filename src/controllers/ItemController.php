<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 11.07.2018
 * Time: 18:43
 */

namespace floor12\mailing\controllers;

use floor12\mailing\models\filters\MailingEmailFilter;
use floor12\mailing\models\MailingFilter;
use yii\web\Controller;
use yii\filters\AccessControl;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\DeleteAction;
use floor12\mailing\models\MailingListItem;
use floor12\mailing\models\filters\MailingListItemFilter;
use yii\filters\VerbFilter;
use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingEmail;
use \Yii;

class ItemController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new MailingListItemFilter();
        $model->load(\Yii::$app->request->get());
        return $this->render('index', ['model' => $model]);
    }

    public function actions()
    {
        return [
            'form' => [
                'class' => EditModalAction::className(),
                'model' => MailingListItem::className(),
                'message' => 'Адрес сохранён',
                'viewParams' => ['lists' => MailingList::find()->forSelect()]
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => MailingListItem::className(),
                'message' => 'Адрес удален'
            ],
        ];
    }


}