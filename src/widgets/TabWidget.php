<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.01.2017
 * Time: 22:12
 */

namespace floor12\mailing\widgets;

use Yii;
use yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Html;

class TabWidget extends Widget
{
    public $linkPostfix;
    public $items;

    public function init()
    {
        $this->items = [
            [
                'name' => Yii::t('mailing', 'Mailing'),
                'href' => Url::toRoute(['/mailing/mailing'])
            ],
            [
                'name' => Yii::t('mailing', 'Address list'),
                'href' => Url::toRoute(['/mailing/list'])
            ],
            [
                'name' => Yii::t('mailing', 'Addresses'),
                'href' => Url::toRoute(['/mailing/item'])
            ],
        ];
    }

    function run(): string
    {

        $active_flag = false;
        $nodes = [];

        if ($this->items) {

            foreach ($this->items as $item) {
                if (strpos($_SERVER['REQUEST_URI'], $item['href']) === 0)
                    $active_flag = true;
            }

            foreach ($this->items as $key => $item) {

                if (!isset($item['visible']) || $item['visible']) {

                    if (($active_flag == false && $key == 0) || (strpos($_SERVER['REQUEST_URI'], $item['href']) === 0))
                        $item['active'] = true;

                    $nodes[] = $this->render('tabWidget', ['item' => $item, 'linkPostfix' => $this->linkPostfix]);
                }
            }
        }
        return Html::tag('ul', implode("\n", $nodes), ['class' => 'nav nav-tabs']);
    }
}