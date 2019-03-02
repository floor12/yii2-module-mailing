<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 20:22
 *
 * @var $model \floor12\mailing\models\MailingListItemBatchForm
 * @var $this \yii\web\View
 * @var $lists array
 *
 */

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= Yii::t('mailing', 'Batch address download') ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'listId')->dropDownList($lists) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 10]) ?>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('mailing', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('mailing', 'Load'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
