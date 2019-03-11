<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 10:24
 *
 * @var %this View
 * @var $model \floor12\mailing\models\filters\MailingListItemFilter
 *
 */

use floor12\editmodal\EditModalHelper;
use floor12\mailing\assets\IconHelper;
use floor12\mailing\assets\MailingAsset;
use floor12\mailing\models\enum\MailingListItemStatus;
use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingListItem;
use floor12\mailing\widgets\TabWidget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


MailingAsset::register($this);

$this->title = Yii::t('app.f12.mailing', 'Addresses');


echo Html::tag('h1', $this->title);

?>

    <div class="pull-right">
        <?php

        echo Html::a(IconHelper::ADD_USER . " " . Yii::t('app.f12.mailing', 'Add recipient'), null, [
                'onclick' => EditModalHelper::showForm(['/mailing/item/form'], 0),
                'class' => 'btn btn-sm btn-default'
            ]) . " ";

        echo Html::a(IconHelper::ADD_BATCH . " " . Yii::t('app.f12.mailing', 'Add batch'), null, [
                'onclick' => EditModalHelper::showForm(['/mailing/item/batch'], 0),
                'class' => 'btn btn-sm btn-default'
            ]) . " ";
        ?>
    </div>

<?php

echo TabWidget::widget([]);


$form = ActiveForm::begin([
    'enableClientValidation' => false,
    'method' => "GET",
    'options' => [
        'class' => 'table-mailing-autosubmit',
        'data-container' => '#items'
    ]]) ?>

    <div class="filter-block">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('app.f12.mailing', 'Filter...')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'list_id')->label(false)->dropDownList(MailingList::find()->forSelect(), ['prompt' => Yii::t('app.f12.mailing', 'All lists')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(MailingListItemStatus::listData(), ['prompt' => Yii::t('app.f12.mailing', 'All statuses')]) ?>
            </div>
        </div>

    </div>

<?php ActiveForm::end();

Pjax::begin(['id' => 'items']);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'tableOptions' => ['class' => 'table table-striped table-banners'],
    'layout' => "{items}\n{pager}\n{summary}",
    'columns' => [
        'id',
        [
            'attribute' => 'email',
            'content' => function (MailingListItem $model): string {
                if ($model->status == MailingListItemStatus::STATUS_UNSUBSCRIBED)
                    $html = Html::tag('span', $model, ['class' => 'striked']);
                else
                    $html = $model;
                return $html;
            }
        ],
        'list',
        [
            'attribute' => 'status',
            'content' => function (MailingListItem $model) {
                return MailingListItemStatus::getLabel($model->status);
            }
        ],
        [
            'contentOptions' => ['class' => 'table-td-control'],
            'class' => \floor12\editmodal\EditModalColumn::class,
        ]
    ]
]);

Pjax::end();

