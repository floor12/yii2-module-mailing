<?php

namespace  floor12\mailing\models;

use app\models\Client;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mailing".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property string $content
 * @property integer $created
 * @property integer $type_id
 * @property array $types
 * @property array $statuses
 * @property string $typeString
 * @property string $statusString
 * @property integer $updated
 * @property integer $send
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $template_id
 * @property Client[] $clients
 * @property array $client_ids
 */
class Mailing extends \yii\db\ActiveRecord
{
    const READ_GIF_URL = "http://s-cadmin.vmserver/mailing/mailview";

    const STATUS_DRAFT = 0;
    const STATUS_WAITING = 1;
    const STATUS_SENDING = 2;
    const STATUS_SEND = 3;

    const TYPE_SIMPLE = 0;
    const TYPE_SHEDULED = 1;

    public $statuses = [
        self::STATUS_DRAFT => "Черновик",
        self::STATUS_WAITING => "В очереди на отправку",
        self::STATUS_SENDING => "Отправляется",
        self::STATUS_SEND => "Отправлено",
    ];

    public $types = [
        self::TYPE_SIMPLE => "Новостная рассылка",
        self::TYPE_SHEDULED => "Событийная",
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content',], 'required'],
            [['status', 'created', 'updated', 'send', 'create_user_id', 'update_user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['client_ids'], 'each', 'rule' => ['integer']],
            [['client_ids'], 'required', 'message' => 'Необходимо выбрать получателей.'],
            ['files', 'file', 'maxFiles' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'statusString' => 'Статус',
            'typeString' => 'Тип',
            'title' => 'Тема',
            'content' => 'Содержание рассылки',
            'created' => 'Создано',
            'updated' => 'Updated',
            'files' => 'Приложенные файлы',
            'send' => 'Отправлено',
            'client_ids' => 'Получатели',
            'clients_total' => 'Получателей',
            'clients_send' => 'Отправлено',
            'clients_opened' => 'Открыто',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
        ];
    }

    public function getStatusString()
    {
        return $this->statuses[$this->status];
    }

    public function getTypeString()
    {
        return $this->types[$this->type_id];
    }

    public function getClients()
    {
        return $this->hasMany(Client::className(), ['user_id' => 'client_id'])
            ->viaTable('mailing_client', ['mailing_id' => 'id']);
    }


    public function getClients_total()
    {
        return $this->getClients()->count();
    }


    public function getClients_send()
    {
        return $this->hasMany(Client::className(), ['user_id' => 'client_id'])
            ->viaTable('mailing_client', ['mailing_id' => 'id'], function ($query) {
                $query->andWhere(['status' => 1]);
            })->count();
    }

    public function getClients_opened()
    {
        return MailView::find()->where(['mailing_id' => $this->id])->count();
    }

    public function beforeSave($insert)
    {
        if (isset(\Yii::$app->components['user'])) {

            if ($this->isNewRecord && !$this->create_user_id) {
                $this->create_user_id = \Yii::$app->user->id;
            }

            if (!$this->update_user_id)
                $this->update_user_id = \Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    public function send()
    {
        $this->status = self::STATUS_WAITING;
        $this->send = null;

        $views = MailView::find()->where(['mailing_id' => $this->id])->all();
        foreach ($views as $view)
            $view->delete();
        return $this->save();
    }


    public function behaviors()
    {
        return [
            'timestamp' => array(
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated'
            ),
            'ManyToManyBehavior' => [
                'class' => \app\components\ManyToManyBehavior::className(),
                'relations' => [
                    'client_ids' => 'clients',
                ],
            ],
            'files' => [
                'class' => \floor12\files\components\FileBehaviour::className(),
                'attributes' => ['files']
            ]
//            'files' => [
//                'class' => \floor12\superfile\SuperfileBehavior::className(),
//                'fields' => [
//                    'files' => [
//                        'title' => 'Прикрепленные файлы',
//                        'preview' => false,
//                        'multiply' => true,
//                        'maxSize' => 5000000,
//                        'button' => 'Добавить файлы',
//                        'showName' => true,
//                        'showControl' => true,
//                        'label' => true,
//                        'successFunction' => 'info("Файл загружен ",1);',
//                        'deleteFunction' => 'info("Файл удален",1);',
//                        'errorFunction' => 'info(message,2);',
//                    ],
//                ]
//            ]

        ];
    }

    public function status($status)
    {
        $this->status = $status;

        if ($status == Mailing::STATUS_SEND)
            $this->send = time();

        $this->save(false);
    }


    public function unsubscribeLink($hash)
    {
        return self::READ_GIF_URL . "?id={$this->id}&hash={$hash}";
    }
}

