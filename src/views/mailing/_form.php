<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 20:22
 *
 * @var $model \floor12\mailing\models\Mailing
 * @var $this \yii\web\View
 * @var $lists array
 * @var $module \floor12\mailing\Module
 *
 */

use Yii;
use floor12\files\components\FileInputWidget;
use floor12\files\logic\ClassnameEncoder;
use floor12\mailing\models\MailingType;
use floor12\summernote\Summernote;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);

$this->registerJs("mailingType()");

?>

<div class="modal-header">
    <h2><?= $model->isNewRecord ? Yii::t('mailing', 'Newsletter creation') : Yii::t('mailing', 'Newsletter update'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'content')->widget(Summernote::class, []) ?>

    <?= $form->field($model, 'files')->widget(FileInputWidget::class, []); ?>


    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'type')->dropDownList(MailingType::$list) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList($model->statuses) ?>
        </div>
    </div>

    <?= $form->field($model, 'emails_array')->widget(Select2::class, [
        'data' => $model->emails_array,
        'pluginOptions' => [
            'tags' => true,
            'multiple' => true
        ]
    ]) ?>

    <?= $form->field($model, 'list_id')->dropDownList($lists, ['prompt' => 'без списка']) ?>


    <div class="mailing-linked-models">
        <?php
        if ($module->linkedModels)
            foreach ($module->linkedModels as $key => $linkedModel) {
                $fieldName = Yii::createObject(ClassnameEncoder::class, [$linkedModel]);
                echo $form->field($model, "external_ids[{$key}]")->label($linkedModel::getMailingLabel())->widget(Select2::class, [
                    'data' => $linkedModel::getMailingList(),
                    'pluginOptions' => [
                        'multiple' => true
                    ]
                ]);
            }
        ?>
    </div>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('mailing', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('mailing', 'Create') : Yii::t('mailing', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
