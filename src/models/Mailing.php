<?php

namespace floor12\mailing\models;

use floor12\files\components\FileBehaviour;
use floor12\mailing\models\enum\MailingStatus;
use floor12\mailing\models\enum\MailingType;
use Yii;

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
 * @property array $emails_array Массив адресов
 * @property int $list_id Список для рассылки
 * @property int $create_user_id Создал
 * @property int $update_user_id Обновил
 * @property array $recipients Массив всех адресов для отправки
 * @property integer $clicks Кол-во кликов по ссылкам из письма
 * @property integer $type Тип рассылки
 *
 * @property MailingEmail[] $emails
 * @property MailingLink[] $links
 * @property MailingExternal[] $externals
 * @property MailingList $list
 * @property MailingStat[] $mailingStat
 * @property MailingUser[] $mailingUsers
 * @property MailingViewed[] $mailingVieweds
 */
class Mailing extends \yii\db\ActiveRecord
{

    public $external_ids = [];

    public $emails_array = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing';
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'title', 'content', 'created', 'updated'], 'required'],
            [['status', 'created', 'updated', 'send', 'list_id', 'create_user_id', 'update_user_id', 'type'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            ['files', 'file', 'maxFiles' => 20],
            ['emails_array', 'each', 'rule' => ['email'], 'message' => 'Есть невалидный адрес'],
            ['status', 'in', 'range' => [
                MailingStatus::STATUS_DRAFT,
                MailingStatus::STATUS_WAITING,
                MailingStatus::STATUS_SENDING,
                MailingStatus::STATUS_SEND
            ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => Yii::t('app.f12.mailing', 'Current state'),
            'title' => Yii::t('app.f12.mailing', 'Subject'),
            'content' => Yii::t('app.f12.mailing', 'Content'),
            'created' => Yii::t('app.f12.mailing', 'Created'),
            'updated' => Yii::t('app.f12.mailing', 'Updated'),
            'send' => Yii::t('app.f12.mailing', 'SendAt'),
            'list_id' => Yii::t('app.f12.mailing', 'Mailing list'),
            'create_user_id' => Yii::t('app.f12.mailing', 'Author'),
            'update_user_id' => Yii::t('app.f12.mailing', 'Updater'),
            'recipient_total' => Yii::t('app.f12.mailing', 'Recipients'),
            'emails_array' => Yii::t('app.f12.mailing', 'Recipients (external)'),
            'files' => Yii::t('app.f12.mailing', 'Attachments'),
            'clicks' => Yii::t('app.f12.mailing', 'Clicks'),
            'views' => Yii::t('app.f12.mailing', 'Views'),
            'type' => Yii::t('app.f12.mailing', 'Mailing type'),
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
    public function getLinks()
    {
        return $this->hasMany(MailingLink::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExternals()
    {
        return $this->hasMany(MailingExternal::className(), ['mailing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingUsers()
    {
        return $this->hasMany(MailingUser::className(), ['mailing_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->loadEmails();
        $this->loadExternals();
        parent::afterFind();
    }

    /**
     * Загружаем связанные модели адресов отправки во временное поле
     * We load the associated dispatch address models into a temporary field.
     */
    public function loadEmails()
    {
        $this->emails_array = [];
        if ($this->emails)
            foreach ($this->emails as $email)
                $this->emails_array[$email->email] = $email->email;
    }

    /**
     * Загружаем связанные внешние адреса из других классов
     * Load related foreign addresses from other classes.
     */
    public function loadExternals()
    {
        $this->external_ids = [];
        if ($this->externals)
            foreach ($this->externals as $external)
                $this->external_ids[array_search($external->class, Yii::$app->getModule('mailing')->linkedModels)][] = $external->object_id;
    }

    /** Подсчет общего количества получателей
     * Counting Total Recipients
     * @return int
     */
    public function getRecipient_total()
    {
        return intval(sizeof($this->recipients));
    }

    /** Общий массив адресов-получателей
     * Common array of recipient addresses
     * @return array
     */
    public function getRecipients()
    {
        switch ($this->type) {
            case MailingType::FREE:
                return $this->getEmails()->select('email')->asArray();

            case MailingType::EXT_CLASS:
                $externalModelsEmails = [];
                if ($this->externals)
                    foreach ($this->externals as $key => $external) {
                        $externalModel = $external->class::findOne($external->object_id);
                        if (!$externalModel->getMailingEmail())
                            continue;

                        $externalModelsEmails[$key]['email'] = $externalModel->getMailingEmail();
                        $externalModelsEmails[$key]['fullname'] = $externalModel->getMailingFullname();
                    }
                return $externalModelsEmails;

            case MailingType::LIST:
                return $this->list ? $this
                    ->list
                    ->getListItemsActive()
                    ->select(['email', 'sex', 'fullname'])
                    ->asArray() : [];

        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(MailingEmail::className(), ['mailing_id' => 'id']);
    }

    /** Подсчет просмотров рассылки
     * Counting mailings
     * @return int
     */
    public function getViews()
    {
        return intval($this->getMailingVieweds()->count());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingVieweds()
    {
        return $this->hasMany(MailingViewed::className(), ['mailing_id' => 'id']);
    }

    /** Подсчет кликов
     * @return int
     */
    public function getClicks()
    {
        return intval($this->getMailingStat()->count());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailingStat()
    {
        return $this->hasMany(MailingStat::className(), ['mailing_id' => 'id']);
    }
}
