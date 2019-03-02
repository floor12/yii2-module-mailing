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

use Yii;
use floor12\mailing\models\MailingListItemSex;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= $model->isNewRecord ? Yii::t('mailing', 'Добавление адреса') : Yii::t('mailing', 'Address editing'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fullname') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'sex')->dropDownList(MailingListItemSex::listData()) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'list_id')->dropDownList($lists) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList($model->statuses) ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('mailing', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('mailing', 'Create') : Yii::t('mailing', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
