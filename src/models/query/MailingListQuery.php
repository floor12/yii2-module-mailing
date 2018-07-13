<?php

namespace floor12\mailing\models\query;

/**
 * This is the ActiveQuery class for [[\floor12\mailing\models\MailingList]].
 *
 * @see \floor12\mailing\models\MailingList
 */
class MailingListQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     */
    public function forSelect()
    {
        return $this->orderBy('title')->select('title')->indexBy('id')->column();
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\MailingList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\MailingList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
