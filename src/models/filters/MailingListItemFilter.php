<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 13:16
 */

namespace floor12\mailing\models\filters;

use floor12\mailing\models\MailingEmail;
use floor12\mailing\models\MailingListItem;
use \yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class MailingListItemFilter extends Model
{
    public $filter;
    public $status;
    public $list_id;

    /**@inheritdoc
     * @return array
     */
    public function rules(): array
    {
        return [
            ['filter', 'string', 'max' => 255],
            [['list_id', 'status'], 'integer']
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
            'query' => MailingListItem::find()
                ->andFilterWhere(['=', 'status', $this->status])
                ->andFilterWhere(['=', 'list_id', $this->list_id])
                ->andFilterWhere(['LIKE', 'email', $this->filter])
        ]);
    }
}