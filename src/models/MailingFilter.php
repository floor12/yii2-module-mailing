<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 10:21
 */

namespace floor12\mailing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class MailingFilter extends Model
{
    public $filter;
    public $type_id;

    private $_query;

    public function getTypes()
    {
        return \Yii::createObject(Mailing::className(), [])->types;
    }

    public function rules()
    {
        return [
            ['filter', 'string', 'max' => 250],
            ['filter', 'trim'],
            ['type_id', 'integer']
        ];
    }

    public function search()
    {
        $this->_query = Mailing::find();

        if ($this->filter) {
            $words = explode(' ', $this->filter);
            $this->_query
                ->andFilterWhere(['LIKE', 'title', $words])
                ->orFilterWhere(['LIKE', 'content', $words])
                ->orFilterWhere(['LIKE', 'content', $words]);
        }

        if ($this->type_id != null)
            $this->_query->andWhere(['type_id' => $this->type_id]);

        return new ActiveDataProvider([
            'query' => $this->_query,
            'pagination' => [
                'pageSize' => 30,
            ]
        ]);
    }
}