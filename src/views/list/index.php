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

use Yii;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use floor12\editmodal\EditModalHelper;
use floor12\mailing\models\MailingList;
use rmrevin\yii\fontawesome\FontAwesome;
use floor12\mailing\widgets\TabWidget;
use floor12\mailing\assets\MailingAsset;
use yii\widgets\ActiveForm;

MailingAsset::register($this);

$this->title = Yii::t('mailing', 'Mailing');

echo Html::tag('h1', $this->title);

echo TabWidget::widget([]);

echo Html::a(FontAwesome::icon('plus') . Yii::t('mailing', 'Create list'), null, [
    'onclick' => EditModalHelper::showForm(['/mailing/list/form'], 0),
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
        <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('mailing', 'Filter...')]) ?>
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
            'attribute' => 'title',
            'content' => function (MailingList $model): string {
                if ($model->status == MailingList::STATUS_DISABLED)
                    $html = Html::tag('span', $model, ['class' => 'striked']);
                else
                    $html = $model;
                return $html;
            }
        ],
        'listItemsActiveCount',
        'itemsUnsubscribedCount',
        [
            'contentOptions' => ['class' => 'table-td-control'],
            'class' => \floor12\editmodal\EditModalColumn::class,
        ]
    ]
]);

Pjax::end();

