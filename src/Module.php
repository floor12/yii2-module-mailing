<?php

namespace floor12\mailing;

use Yii;
use yii\base\InvalidConfigException;

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
    public $defaultRoute = 'mailing/index';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'floor12\mailing\controllers';

    /** Путь к макету, который используется в контроллерах управления рассылками
     * @var string
     */
    public $layoutBackend = '@app/views/layouts/main';

    /** Путь к макету, который используется в публичных контроллерах
     * @var string
     */
    public $layoutFrontend = '@app/views/layouts/main';

    /**
     * Те роли в системе, которым разрешено редактирование новостей
     * @var array
     */
    public $editRole = '@';

    /** Домен для простановки ссылок статистики.
     * Так как отправка идет из консоли, то приложение ничего не знает о домене, на котором оно запускатся.
     * Поэтому необходимо прописать домен отедльно.
     * @var string
     */
    public $domain = "https://exemple.com";

    /** По причинам, указанным выше, маршруты статистики и редиректа для ссылок можно переопределить.
     * @var string
     */
    public $statRoute = "/mailing/stat/gif?id={id}&hash={hash}";

    /**
     * @var string
     */
    public $redirectRoute = "/mailing/stat/link?hash={hash}";

    /**
     * @var string
     */
    public $unsubscribeRoute = "/mailing/stat/unsubscribe";

    /** Имя шаблона для отправки рассылок
     * @var string
     */
    public $htmlTemplate;

    /** Адрес электронной почты, который подставлять в отправителя
     * @var string
     */
    public $fromEmail;

    /** Имя отправителя рассылок
     * @var string
     */
    public $fromName;

    /** Массив для перечисления моделей системы, которые нужно использовать для отправки почты
     * @var array
     */
    public $linkedModels = [];


    /** Во время инициализации проверяем все ли необходимые параметры сконфигурированы
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->fromEmail)
            throw new InvalidConfigException(Yii::t('mailing', 'No parameter specified in module configuration {0}', '$fromEmail'));

        if (!$this->fromName)
            throw new InvalidConfigException(Yii::t('mailing', 'No parameter specified in module configuration {0}', '$fromName'));

        if (!$this->htmlTemplate)
            throw new InvalidConfigException(Yii::t('mailing', 'No parameter specified in module configuration {0}', '$htmlTemplate'));

        if (!$this->domain)
            throw new InvalidConfigException(Yii::t('mailing', 'No parameter specified in module configuration {0}', '$domain'));

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/mailing/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@kivicms/mailing/messages',
            'fileMap' => [
                'mailing' => 'mailing.php',
            ],
        ];
    }

    public function makeStatGifUrl($id, $hash)
    {
        return $this->domain . str_replace(['{id}', '{hash}'], [$id, $hash], $this->statRoute);
    }

    public function makeRedirectUrl($hash)
    {
        return $this->domain . str_replace('{hash}', $hash, $this->redirectRoute);
    }

}
