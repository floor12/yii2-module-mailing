<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_user".
 *
 * @property int $mailing_id Связь с рассылкой
 * @property int $user_id Связь с пользователем
 *
 * @property Mailing $mailing
 */
class MailingUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'user_id'], 'required'],
            [['mailing_id', 'user_id'], 'integer'],
            [['mailing_id', 'user_id'], 'unique', 'targetAttribute' => ['mailing_id', 'user_id']],
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
            'user_id' => Yii::t('mailing', 'The link to the user'),
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
     * @return \floor12\mailing\models\query\MailingUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingUserQuery(get_called_class());
    }
}
