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
use floor12\mailing\models\filters\MailingEmailFilter;
use floor12\mailing\models\filters\MailingListItemFilter;
use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingListItem;
use floor12\mailing\models\MailingListItemBatchForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ItemController extends Controller
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
        $model = new MailingListItemFilter();
        $model->load(\Yii::$app->request->get());
        return $this->render('index', ['model' => $model]);
    }
    

    public function actions()
    {
        return [
            'form' => [
                'class' => EditModalAction::class,
                'model' => MailingListItem::class,
                'message' => Yii::t('mailing', 'Address saved'),
                'viewParams' => ['lists' => MailingList::find()->forSelect()]
            ],
            'batch' => [
                'class' => EditModalAction::class,
                'model' => MailingListItemBatchForm::class,
                'message' => Yii::t('mailing', 'Address saved'),
                'view' => '_batch',
                'viewParams' => ['lists' => MailingList::find()->forSelect()]
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => MailingListItem::class,
                'message' => Yii::t('mailing', 'Address deleted')
            ],
        ];
    }


}