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
 * @property MailingListItem[] $listItemsActive
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
     * @return \floor12\mailing\models\query\MailingListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\mailing\models\query\MailingListQuery(get_called_class());
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
            'title' => Yii::t('mailing', 'List name'),
            'status' =>  Yii::t('mailing', 'Hide'),
            'listItemsActiveCount' =>  Yii::t('mailing', 'Active addresses'),
            'itemsUnsubscribedCount' => Yii::t('mailing', 'Unsubscribe'),
        ];
    }

    /**
     * @return integer
     */
    public function getListItemsActiveCount()
    {
        return $this->getListItemsActive()->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListItemsActive()
    {
        return $this->getListItems()->active();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListItems()
    {
        return $this->hasMany(MailingListItem::className(), ['list_id' => 'id']);
    }

    /**
     * @return integer
     */
    public function getItemsUnsubscribedCount()
    {
        return $this->getListItemsUnsubscribed()->count();
    }

    /**
     * @return mixed
     */
    public function getListItemsUnsubscribed()
    {
        return $this->getListItems()->unsubscribed();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
