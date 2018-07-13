<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 10:24
 *
 * @var %this View
 * @var $model \floor12\mailing\models\MailingFilter
 *
 */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use rmrevin\yii\fontawesome\FontAwesome;
use floor12\mailing\assets\MailingAsset;
use floor12\banner\models\AdsBanner;
use floor12\editmodal\EditModalHelper;
use yii\widgets\ActiveForm;
use \floor12\mailing\widgets\TabWidget;
use floor12\mailing\models\Mailing;
use \yii\helpers\Url;

MailingAsset::register($this);

$this->title = 'Рассылки';

$this->registerJs("routeMailingSend='" . Url::toRoute(['/mailing/mailing/send']) . "'");

echo Html::tag('h1', $this->title);

echo TabWidget::widget([]);

echo Html::a(FontAwesome::icon('plus') . " создать рассылку", null, [
    'onclick' => EditModalHelper::showForm(['/mailing/mailing/form'], 0),
    'class' => 'btn btn-sm btn-success btn-mailing-add'
]);


$form = ActiveForm::begin([
    'enableClientValidation' => false,
    'method' => "GET",
    'options' => [
        'class' => 'table-mailing-autosubmit',
        'data-container' => '#items'
    ]]) ?>

    <div class="filter-block">
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => 'Фильтр...']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(Yii::createObject(Mailing::class, [])->statuses, ['prompt' => 'все статусы']) ?>
            </div>
        </div>

    </div>

<?
ActiveForm::end();

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped table-banners'],
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        [
            'attribute' => 'title',
            'content' => function (Mailing $model): string {
                $html = $model->title;
                return $html;
            }
        ],
        'status_string',
        'recipient_total',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (Mailing $model) {
                $ret = Html::a(FontAwesome::icon('pencil'), NULL, ['onclick' => EditModalHelper::showForm(['/mailing/mailing/form'], $model->id), 'class' => 'btn btn-default btn-sm']) . " ";
                $ret .= Html::a(FontAwesome::icon('trash'), NULL, ['onclick' => EditModalHelper::deleteItem(['/mailing/mailing/delete'], $model->id), 'class' => 'btn btn-default btn-sm']) . " ";
                if ($model->status == Mailing::STATUS_DRAFT)
                    $ret .= Html::a(FontAwesome::icon('send'), NULL, ['onclick' => "sendMailing({$model->id})", 'class' => 'btn btn-success btn-sm']) . " ";
                return $ret;
            },
        ]
    ]
]);

Pjax::end();

