<?php

namespace floor12\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_list".
 *
 * @property int $id
 * @property string $title Название списка
 * @property int $status Скрыть
 *
 * @property MailingListItem[] $listItems
 */
class MailingList extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_DISABLED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название списка',
            'status' => 'Скрыть',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListItems()
    {
        return $this->hasMany(MailingListItem::className(), ['list_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\query\MailingListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingListQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
