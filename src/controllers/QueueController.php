<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 15.07.2018
 * Time: 16:13
 */

namespace floor12\mailing\controllers;

use floor12\mailing\logic\MailingQueueRun;
use Yii;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionIndex()
    {
        echo Yii::createObject(MailingQueueRun::class, [])->execute() . PHP_EOL;

    }

}