<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-02-28
 * Time: 12:43
 *
 * @var $this \yii\base\View
 * @var $content string
 * @var $gifUrl string
 * @var $unsubscribeUrl string
 */

use yii\helpers\Html;

?>

<?= $content ?>

<?= Html::img($gifUrl) ?>

<?php if ($unsubscribeUrl): ?>
    <small>Если вы не хотите получать от нас письма этого типа и удалить свой адрес из списка получателей, вы можете
        <?= Html::a("отписаться от рассылки", $unsubscribeUrl) ?>.
    </small>
<?php endif; ?>
