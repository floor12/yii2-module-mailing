<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_link".
 *
 * @property int $id
 * @property int $mailing_id Связь с рассылкой
 * @property string $link Тело ссылки
 * @property string $hash Hash
 *
 * @property Mailing $mailing
 * @property MailingStat[] $mailingStats
 */
class MailingLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'link', 'hash'], 'required'],
            [['mailing_id'], 'integer'],
            [['link', 'hash'], 'string', 'max' => 255],
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
            'link' => Yii::t('mailing', 'Link body'),
            'hash' => 'Hash',
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
     * @return \yii\db\ActiveQuery
     */
    public function getMailingStats()
    {
        return $this->hasMany(MailingStat::className(), ['link_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingLinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingLinkQuery(get_called_class());
    }
}
