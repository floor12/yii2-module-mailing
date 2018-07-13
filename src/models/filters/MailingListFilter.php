<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 13:16
 */

namespace floor12\mailing\models\filters;

use floor12\mailing\models\MailingList;
use \yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class MailingListFilter extends Model
{
    public $filter;

    /**@inheritdoc
     * @return array
     */
    public function rules(): array
    {
        return [
            ['filter', 'string', 'max' => 255]
        ];
    }

    /**
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function dataProvider(): ActiveDataProvider
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Model validation error');

        return new ActiveDataProvider([
            'query' => MailingList::find()->andFilterWhere(['LIKE', 'title', $this->filter])
        ]);
    }
}