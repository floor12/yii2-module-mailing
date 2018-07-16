<?php

namespace floor12\mailing\models;

/**
 * This is the model class for table "mailing_external".
 *
 * @property int $mailing_id
 * @property string $class
 * @property int $object_id
 */
class MailingExternal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_external';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailing_id', 'class', 'object_id'], 'required'],
            [['mailing_id', 'object_id'], 'integer'],
            [['class'], 'string', 'max' => 255],
            [['mailing_id', 'class', 'object_id'], 'unique', 'targetAttribute' => ['mailing_id', 'class', 'object_id']],
            [['mailing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mailing::className(), 'targetAttribute' => ['mailing_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mailing_id' => 'Mailing ID',
            'class' => 'Class',
            'object_id' => 'Object ID',
        ];
    }
}
