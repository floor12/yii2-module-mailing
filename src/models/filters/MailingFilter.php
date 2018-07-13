<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.07.2018
 * Time: 10:21
 */

namespace floor12\mailing\models\filters;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use floor12\mailing\models\Mailing;

class MailingFilter extends Model
{
    public $filter;
    public $status;

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
            ['status', 'integer']
        ];
    }

    public function dataProvider()
    {
        $this->_query = Mailing::find();

        if ($this->filter) {
            $words = explode(' ', $this->filter);
            $this->_query
                ->andFilterWhere(['LIKE', 'title', $words])
                ->orFilterWhere(['LIKE', 'content', $words])
                ->orFilterWhere(['LIKE', 'content', $words]);
        }

        if ($this->status != null)
            $this->_query->andWhere(['status' => $this->status]);

        return new ActiveDataProvider([
            'query' => $this->_query,
            'pagination' => [
                'pageSize' => 30,
            ]
        ]);
    }
}