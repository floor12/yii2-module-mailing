<?php

namespace floor12\mailing;

/**
 * Mailing module definition class
 * @property  string $editRole
 * @property  string $controllerNamespace
 * @property  string $layout
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'floor12\mailing\controllers';

    /** Путь к макету, который используется в контроллерах управления рассылками
     * @var string
     */
    public $layout;

    /**
     * Те роли в системе, которым разрешено редактирование новостей
     * @var array
     */
    public $editRole = '@';

}
