<?php

namespace floor12\mailing\models;


use Yii;
/**
 * This is the model class for table "mailing_list_item".
 *
 * @property int $id
 * @property int $list_id Связь со списком
 * @property string $email Email
 * @property int $status Статус
 * @property int $fullname Полное имя
 * @property int $sex Пол получателя
 * @property string $status_string Статус
 *
 * @property MailingList $list
 */
class MailingListItem extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_UNSUBSCRIBED = 1;

    // Todo: Статусы!
    public $statuses = [
        self::STATUS_ACTIVE => 'Активный',
        self::STATUS_UNSUBSCRIBED => 'Отписался',
    ];

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
        return 'mailing_list_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['fullname', 'string'],
            [['list_id', 'email'], 'required'],
            [['list_id', 'status', 'sex'], 'integer'],
            [['email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetAttribute' => ['email', 'list_id'], 'message' => 'Этот адрес уже есть в этом списке'],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => MailingList::className(), 'targetAttribute' => ['list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'list_id' => Yii::t('mailing', 'The link to the newsletter'),
            'list' => Yii::t('mailing', 'List'),
            'email' => 'Email',
            'status' => Yii::t('mailing', 'Status'),
            'status_string' => Yii::t('mailing', 'Status'),
            'sex' => Yii::t('mailing', 'Recipient gender'),
            'fullname' => Yii::t('mailing', 'Recipient name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(MailingList::className(), ['id' => 'list_id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingListItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingListItemQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }
}
