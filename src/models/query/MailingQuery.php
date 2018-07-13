<?php

namespace floor12\mailing\models\query;

/**
 * This is the ActiveQuery class for [[\floor12\mailing\models\Mailing]].
 *
 * @see \floor12\mailing\models\Mailing
 */
class MailingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\Mailing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\mailing\models\Mailing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
