<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 18:07
 */

namespace floor12\mailing\assets;

use yii\web\AssetBundle;

class MailingAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-module-mailing/src/assets';

    public $css = [
        'mailing.admin.css'
    ];

    public $js = [
        'mailing.admin.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'floor12\editmodal\EditModalAsset'
    ];
}
