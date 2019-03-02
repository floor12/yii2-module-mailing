<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-03-01
 * Time: 21:44
 */

namespace floor12\mailing\models;


use floor12\mailing\logic\AddressLoader;
use Yii;
use yii\base\Model;

class MailingListItemBatchForm extends Model
{
    public $listId;
    public $data;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['listId', 'data'], 'required'],
            ['listId', 'integer'],
            ['data', 'string'],
            [['listId'], 'exist', 'skipOnError' => true, 'targetClass' => MailingList::class, 'targetAttribute' => ['listId' => 'id']],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate())
            return false;

        $list = MailingList::findOne($this->listId);
        return Yii::createObject(AddressLoader::class, [$list, $this->data])->execute();
    }

    public function attributeLabels()
    {
        return [
            'listId' => Yii::t('mailing', 'Mailing list'),
            'data' => Yii::t('mailing', 'Addresses'),
        ];
    }

}