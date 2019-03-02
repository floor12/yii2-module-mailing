<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_stat".
 *
 * @property int $id
 * @property int $mailing_id Связь с рассылкой
 * @property int $link_id Связь c ссылкой
 * @property int $timestamp Временная метка
 *
 * @property MailingLink $link
 * @property Mailing $mailing
 */
class MailingStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'link_id', 'timestamp'], 'required'],
            [['mailing_id', 'link_id', 'timestamp'], 'integer'],
            [['link_id'], 'exist', 'skipOnError' => true, 'targetClass' => MailingLink::className(), 'targetAttribute' => ['link_id' => 'id']],
            [['mailing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mailing::className(), 'targetAttribute' => ['mailing_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mailing_id' => Yii::t('mailing', 'The link to the newsletter'),
            'link_id' => Yii::t('mailing', 'The link to the link'),
            'timestamp' => Yii::t('mailing', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(MailingLink::className(), ['id' => 'link_id']);
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
     * @return \floor12\mailing\models\query\MailingStatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingStatQuery(get_called_class());
    }
}
