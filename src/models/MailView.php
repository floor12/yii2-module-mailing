<?php

namespace  floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "delivery_view".
 *
 * @property integer $id
 * @property integer $mailing_id
 * @property string $hash
 */
class MailView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mailing_id'], 'required'],
            [['mailing_id'], 'integer'],
            [['hash'], 'unique', 'targetAttribute' => ['hash', 'mailing_id']],
            [['hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mailing_id' => 'Mailing ID',
            'hash' => 'Hash',
        ];
    }
}
