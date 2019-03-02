<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 20:22
 *
 * @var $model \magazine\models\mailing\MailingList
 * @var $this \yii\web\View
 *
 */

use Yii;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);


?>
<div class="modal-header">
    <h2><?= $model->isNewRecord ? Yii::t('mailing', 'Create address list') : Yii::t('mailing', 'Update address list'); ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-2" style="padding-top: 27px">
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('mailing', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('mailing', 'Create') : Yii::t('mailing', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
