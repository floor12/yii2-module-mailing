<?php

namespace floor12\mailing\models\query;

use floor12\mailing\models\enum\MailingListItemStatus;

/**
 * This is the ActiveQuery class for [[\floor12\mailing\models\MailingListItem]].
 *
 * @see \floor12\mailing\models\MailingListItem
 */
class MailingListItemQuery extends \yii\db\ActiveQuery
{
    /**
     * @return MailingListItemQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => MailingListItemStatus::STATUS_ACTIVE]);
    }

    /**
     * @return MailingListItemQuery
     */
    public function unsubscribed()
    {
        return $this->andWhere(['status' => MailingListItemStatus::STATUS_UNSUBSCRIBED]);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\MailingListItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\MailingListItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
