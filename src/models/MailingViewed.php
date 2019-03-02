<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_viewed".
 *
 * @property int $mailing_id Связь с рассылкой
 * @property string $hash Уникальный хеш для статистики
 *
 * @property Mailing $mailing
 */
class MailingViewed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_viewed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'hash'], 'required'],
            [['mailing_id'], 'integer'],
            [['hash'], 'string', 'max' => 255],
            [['mailing_id', 'hash'], 'unique', 'targetAttribute' => ['mailing_id', 'hash']],
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
            'hash' => Yii::t('mailing', 'Unique hash for statistics'),
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
     * @return \floor12\mailing\models\query\MailingViewedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingViewedQuery(get_called_class());
    }
}
