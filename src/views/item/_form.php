<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 20:22
 *
 * @var $model \floor12\mailing\models\MailingListItem
 * @var $this \yii\web\View
 * @var $lists array
 *
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= $model->isNewRecord ? "Добавление адреса" : "Редактирование адреса"; ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'list_id')->dropDownList($lists) ?>
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
