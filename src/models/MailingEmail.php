<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_email".
 *
 * @property int $mailing_id Связь с рассылкой
 * @property string $email Email
 *
 * @property Mailing $mailing
 */
class MailingEmail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'email'], 'required'],
            [['mailing_id'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['mailing_id', 'email'], 'unique', 'targetAttribute' => ['mailing_id', 'email']],
            [['mailing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mailing::className(), 'targetAttribute' => ['mailing_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mailing_id' => Yii::t('mailing', 'The link to the newsletter'),
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailing()
    {
        return $this->hasOne(Mailing::className(), ['id' => 'mailing_id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingEmailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingEmailQuery(get_called_class());
    }
}
