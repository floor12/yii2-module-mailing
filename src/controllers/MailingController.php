<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 11.07.2018
 * Time: 18:43
 */

namespace floor12\mailing\controllers;

use yii\web\Controller;

class MailingController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['mailview'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }

    public function actionMailview($hash, $id)
    {
        \Yii::createObject(MailViewed::class, [$id, $hash])->execute();
        header('Content-Type: image/gif');
        return readfile(\Yii::getAlias('@webroot/images') . '/1x1.gif');
    }

    public function actionSend($id)
    {
        $model = Mailing::findOne($id);
        if (!$model)
            throw new NotFoundHttpException("Рассылка  {$id} не найдена");

        if (!$model->send())
            throw new BadRequestHttpException("Не удалось отправить рассылку");

    }


    public function actionIds($mode)
    {
        $query = Client::find()->select('user_id')->joinWith('bases');

        if ($mode == 'active') {
            $query->where(['=', 'bases.is_available', Base::STATUS_AVAILABLE]);
        }

        if ($mode == 'inactive') {
            $query->where(['=', 'bases.is_available', Base::STATUS_DISABLED]);
        }

        return json_encode($query->column());
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