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
 *
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use marqu3s\summernote\Summernote;
use kartik\select2\Select2;
use floor12\files\components\FileInputWidget;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);

?>

<div class="modal-header">
    <h2><?= $model->isNewRecord ? "Создание рассылки" : "Редактирование рассылки"; ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'content')->widget(Summernote::class, []) ?>

    <?= $form->field($model, 'files')->widget(FileInputWidget::class, []); ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'emails_array')->widget(Select2::class, [
                'data' => $model->emails_array,
                'pluginOptions' => [
                    'tags' => true,
                    'multiple' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'list_id')->dropDownList($lists, ['prompt' => 'без списка']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList($model->statuses) ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>