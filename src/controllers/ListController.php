<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 11.07.2018
 * Time: 18:43
 */

namespace floor12\mailing\controllers;

use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\mailing\models\filters\MailingListFilter;
use floor12\mailing\models\MailingList;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ListController extends Controller
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
        $model = new MailingListFilter();
        $model->load(\Yii::$app->request->get());
        return $this->render('index', ['model' => $model]);
    }

    public function actions()
    {
        return [
            'form' => [
                'class' => EditModalAction::className(),
                'model' => MailingList::className(),
                'message' => Yii::t('mailing', 'List saved')
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => MailingList::className(),
                'message' => Yii::t('mailing', 'List deleted')
            ],
        ];
    }


}