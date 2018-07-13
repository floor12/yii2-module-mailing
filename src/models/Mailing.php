<?php

namespace floor12\mailing\models;

use Yii;
use floor12\files\components\FileBehaviour;

/**
 * This is the model class for table "mailing".
 *
 * @property int $id
 * @property int $status Текущее состояние
 * @property string $title Заголовок
 * @property string $content Содержание
 * @property int $created Создана
 * @property int $updated Обновлена
 * @property int $send Отправлена
 * @property int $recipient_total Всего получателей
 * @property int $emails_array Массив адресов
 * @property int $list_id Список для рассылки
 * @property int $create_user_id Создал
 * @property int $update_user_id Обновил
 *
 * @property MailingEmail[] $emails
 * @property MailingLink[] $mailingLinks
 * @property MailingList $list
 * @property MailingStat[] $mailingStats
 * @property MailingUser[] $mailingUsers
 * @property MailingViewed[] $mailingVieweds
 */
class Mailing extends \yii\db\ActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_WAITING = 1;
    const STATUS_SENDING = 2;
    const STATUS_SEND = 3;

    public $statuses = [
        self::STATUS_DRAFT => "Черновик",
        self::STATUS_WAITING => "В очереди на отправку",
        self::STATUS_SENDING => "Отправляется",
        self::STATUS_SEND => "Отправлено",
    ];

    public $emails_array = [];

    /**
     * @return string
     */
    public function getStatus_string()
    {
        return $this->statuses[$this->status];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'title', 'content', 'created', 'updated'], 'required'],
            [['status', 'created', 'updated', 'send', 'list_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            ['files', 'file', 'maxFiles' => 20],
            ['emails_array', 'each', 'rule' => ['email'], 'message' => 'Есть невалидный адрес']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Текущее состояние',
            'status_string' => 'Текущее состояние',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'created' => 'Создана',
            'updated' => 'Обновлена',
            'send' => 'Отправлена',
            'list_id' => 'Список для рассылки',
            'create_user_id' => 'Создал',
            'update_user_id' => 'Обновил',
            'recipient_total' => 'Получателей',
            'emails_array' => 'Получатели (внешние)',
            'files' => 'Приложения'
        ];
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'files' => [
                'class' => FileBehaviour::class,
                'attributes' => ['files']
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(MailingList::class, ['id' => 'list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(MailingEmail::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingLinks()
    {
        return $this->hasMany(MailingLink::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingStats()
    {
        return $this->hasMany(MailingStat::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingUsers()
    {
        return $this->hasMany(MailingUser::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingVieweds()
    {
        return $this->hasMany(MailingViewed::className(), ['mailing_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingQuery(get_called_class());
    }

    public function afterFind()
    {
        $this->loadEmails();
        parent::afterFind();
    }

    public function loadEmails()
    {
        $this->emails_array = [];
        if ($this->emails)
            foreach ($this->emails as $email)
                $this->emails_array[$email->email] = $email->email;
    }

    public function getRecipient_total()
    {
        $externalEmailsCount = $this->getEmails()->count();
        $listEmailsCount = $this->list ? $this->list->getListItems()->count() : 0;
        return $externalEmailsCount + $listEmailsCount;
    }
}
