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
 * @property string $hash Hash
 *
 * @property MailingList $list
 */
class MailingListItem extends \yii\db\ActiveRecord
{

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
            'list_id' => Yii::t('app.f12.mailing', 'The link to the newsletter'),
            'list' => Yii::t('app.f12.mailing', 'List'),
            'email' => 'Email',
            'status' => Yii::t('app.f12.mailing', 'Status'),
            'status_string' => Yii::t('app.f12.mailing', 'Status'),
            'sex' => Yii::t('app.f12.mailing', 'Recipient gender'),
            'fullname' => Yii::t('app.f12.mailing', 'Recipient name'),
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
