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

use floor12\editmodal\EditModalHelper;
use floor12\mailing\assets\IconHelper;
use floor12\mailing\assets\MailingAsset;
use floor12\mailing\models\enum\MailingStatus;
use floor12\mailing\models\enum\MailingType;
use floor12\mailing\models\Mailing;
use floor12\mailing\widgets\TabWidget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

MailingAsset::register($this);

$this->title = Yii::t('app.f12.mailing', 'Newsletters');

$this->registerJs("routeMailingSend='" . Url::toRoute(['/mailing/mailing/send']) . "'");

echo Html::tag('h1', $this->title);

echo Html::tag('div',
    Html::a(IconHelper::PLUS . " " . Yii::t('app.f12.mailing', 'Create a newsletter'), null, [
        'onclick' => EditModalHelper::showForm(['/mailing/mailing/form'], 0),
        'class' => 'btn btn-sm btn-default'
    ]),
    ['class' => 'pull-right']);

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
            <div class="col-md-9">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('app.f12.mailing', 'Filter...')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(MailingStatus::listData(), ['prompt' => Yii::t('app.f12.mailing', 'All statuses')]) ?>
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
            'attribute' => 'title',
            'content' => function (Mailing $model): string {
                $html = $model->title;
                $html .= Html::tag('div', MailingType::getLabel($model->type), ['class' => 'small']);
                return $html;
            }
        ],
        [
            'attribute' => 'status',
            'content' => function (Mailing $model) {
                $html = MailingStatus::getLabel($model->status);
                if ($model->status == MailingStatus::STATUS_SEND)
                    $html .= Html::tag('div', Yii::$app->formatter->asDatetime($model->send), ['class' => 'small']);
                return $html;
            }
        ],
        'recipient_total',
        'views',
        'clicks',
        ['contentOptions' => ['style' => 'min-width:100px; text-align:right;'],
            'content' => function (Mailing $model) {
                $ret = '';
                if ($model->status == MailingStatus::STATUS_DRAFT)
                    $ret .= Html::a(IconHelper::SEND, NULL, ['onclick' => "sendMailing({$model->id})", 'class' => 'btn btn-default btn-sm']) . " ";
                $ret .= EditModalHelper::editBtn('/mailing/mailing/form', $model->id);
                $ret .= EditModalHelper::deleteBtn('/mailing/mailing/delete', $model->id);
                return $ret;
            },
        ]
    ]
]);

Pjax::end();

