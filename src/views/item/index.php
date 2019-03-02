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

use Yii;
use floor12\editmodal\EditModalHelper;
use floor12\mailing\assets\MailingAsset;
use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingListItem;
use floor12\mailing\widgets\TabWidget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

MailingAsset::register($this);

$this->title = Yii::t('mailing', 'Mailing');

$svgPlus = '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-plus" class="svg-inline--fa fa-user-plus fa-w-20" role="img" viewBox="0 0 640 512"><path fill="currentColor" d="M624 208h-64v-64c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v64h-64c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h64v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-64h64c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm-400 48c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>';
$svgPlusBatch = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="folder-plus" class="svg-inline--fa fa-folder-plus fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 128H272l-64-64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm-96 168c0 8.84-7.16 16-16 16h-72v72c0 8.84-7.16 16-16 16h-16c-8.84 0-16-7.16-16-16v-72h-72c-8.84 0-16-7.16-16-16v-16c0-8.84 7.16-16 16-16h72v-72c0-8.84 7.16-16 16-16h16c8.84 0 16 7.16 16 16v72h72c8.84 0 16 7.16 16 16v16z"></path></svg>';

echo Html::tag('h1', $this->title);

?>

    <div class="pull-right">
        <?php

        echo Html::a($svgPlus . Yii::t('mailing', 'Add recipient'), null, [
                'onclick' => EditModalHelper::showForm(['/mailing/item/form'], 0),
                'class' => 'btn btn-sm btn-default'
            ]) . " ";

        echo Html::a($svgPlusBatch . Yii::t('mailing', 'Add list'), null, [
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
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('mailing', 'Filter...')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'list_id')->label(false)->dropDownList(MailingList::find()->forSelect(), ['prompt' => Yii::t('mailing', 'All lists')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(Yii::createObject(MailingListItem::class, [])->statuses, ['prompt' => Yii::t('mailing', 'All statuses')]) ?>
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
                if ($model->status == MailingListItem::STATUS_UNSUBSCRIBED)
                    $html = Html::tag('span', $model, ['class' => 'striked']);
                else
                    $html = $model;
                return $html;
            }
        ],
        'list',
        'status_string',
        [
            'contentOptions' => ['class' => 'table-td-control'],
            'class' => \floor12\editmodal\EditModalColumn::class,
        ]
    ]
]);

Pjax::end();

