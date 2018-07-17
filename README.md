# yii2-module-mailing
[![Build Status](https://travis-ci.org/floor12/yii2-module-mailing.svg?branch=master)](https://travis-ci.org/floor12/yii2-module-mailing)
[![Latest Stable Version](https://poser.pugx.org/floor12/yii2-module-mailing/v/stable)](https://packagist.org/packages/floor12/yii2-module-mailing)
[![Latest Unstable Version](https://poser.pugx.org/floor12/yii2-module-mailing/v/unstable)](https://packagist.org/packages/floor12/yii2-module-mailing)
[![Total Downloads](https://poser.pugx.org/floor12/yii2-module-mailing/downloads)](https://packagist.org/packages/floor12/yii2-module-mailing)
[![License](https://poser.pugx.org/floor12/yii2-module-mailing/license)](https://packagist.org/packages/floor12/yii2-module-mailing)

Данный модуль разработан для отправки массовых рассылок (как пользователям приложения, так и по произвольным спискам). 
Он отслеживает открытые письма (при включенной загрузке изображений), 
а так же отслеживает открытые ссылки, при наличии их в рассылке.

**Отправка рассылок возможна на:**
- произвольный список email адресов, введенных непосредственно при составлении рассылки;
- по заранее составленным спискам 
(их можно составлять в интерфейсе админки модуля, но часто бывает удобно составлять их динамически самостоятельно);
- извлекая адреса из одной или нескольких ActiveRecord моделей вашего приложения.


Установка
------------

#### Ставим модуль

Выполняем команду
```bash
$ composer require floor12/yii2-module-mailing
```

иди добавляем в секцию "requred" файла composer.json
```json
"floor12/yii2-module-mailing": "*"
```


Выполняем миграцию для созданию необходимых таблиц
```bash
$ ./yii migrate --migrationPath=@vendor/floor12/yii2-module-mailing/src/migrations/
```

Добавляем данный модуль в конфиг приложения (а так же модуль `floor12/yii2-module-files`, 
если он не был установлен в приложении прежде)
```php  
'modules' => [
        'mailing' => [
            'class' => 'floor12\mailing\Module',
            'editRole' => 'admin',
            'layout' => '@app/views/layouts/columns',
            'fromEmail' => 'no-reply@example.com',
            'fromName' => 'Служба рассылок сайта example.com',
            'htmlTemplate' => 'mailing-main',
            'domain' => 'https://aexample.com',
            'linkedModels' => [
                \common\models\User::class,
                \common\models\Clients::class,
            ]
        ],
        'files' => [
            'class' => 'floor12\files\Module',
        ],
    ],
    ...
```

**Параметры**:

- `editRole` - роль пользователя, который имеет доступ к контроллерам админки модуля.

- `layout` - алиас лейаута, которые необходимо использовать в админке модуля.
- `fromEmail` - email адрес отправителя рассылок.
- `fromName` - От имени кого отправляются рассылки.
- `htmlTemplate` - название темплейта в проекте, которые используется для рассылок
- `domain` - так как запуск очереди идет через консоль, а она ничего не знает о домене сайта, 
то прописываем домен приложения, для работы отслеживания открытых писем и переходов по ссылкам. 
- `linkedModels` - Массив классов ActiveRecord, данные из которых необходимо использовать для формирования адресов получателей. 
Данные классы  должны имплементировать `floor12\mailing\interfaces\MailingRecipientInterface`.

**Запуск очереди**

Для запуска очередь необходимо выполнить команду:
```
$./yii mailing/queue
```

Рекомендую добавить ее в крон с периодичностью минут в 15. 


Использование
------------

Админка модуля доступна по адресу `https://example.com/mailing`.

**Раздел рассылок:**
![Image](https://floor12.net/images/yii2-module-mailing-index.png)
**Раздел списков:**
![Image](https://floor12.net/images/yii2-module-mailing-list.png)
**Раздел адресов:**
![Image](https://floor12.net/images/yii2-module-mailing-items.png)

При редактировании рассылки, если в конфиге модуля были указаны какие-либо классы в массиве `linkedModels`,
то компоненты Select2 с выбором объектов этих классов будут сформированы автоматически. На изображениях показан пример
с классом `common\models\User`. 

![Image](https://floor12.net/images/yii2-module-mailing-update.png)

В примере на изображении класс `User` реализует  `floor12\mailing\interfaces\MailingRecipientInterface` следующим образом, 
благодаря чему, в форме генерируется поле "пользователи".
```php  
class User extends MyActiveRecord implements IdentityInterface, MailingRecipientInterface
{

    public static function getMailingList(): array
    {
        return self::find()->select('fullname')->indexBy('id')->column();
    }

    public static function getMailingLabel(): string
    {
        return "Пользователи";
    }

    public function getMailingEmail(): string
    {
        return $this->email;
    }
    ...
```

Остальные подробности можно узнать из исходного кода.

