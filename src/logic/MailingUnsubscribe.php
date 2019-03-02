<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 12.08.2018
 * Time: 19:24
 */

namespace floor12\mailing\logic;


use floor12\mailing\models\MailingListItem;
use Yii;
use yii\web\BadRequestHttpException;

class MailingUnsubscribe
{
    private $_email;
    private $_hash;
    private $_list_id;

    const SALT = "fK99Djd9fj2S";

    public function __construct(string $email, int $list_id, string $hash)
    {
        $this->_email = $email;
        $this->_list_id = $list_id;
        $this->_hash = $hash;
    }

    public function execute()
    {
        if (!self::checkHash($this->_email, $this->_list_id, $this->_hash))
            throw new BadRequestHttpException('Wrong hash');

        $emailItem = MailingListItem::find([
            'email' => $this->_email,
            'list_id' => $this->_list_id,
            'status' => MailingListItem::STATUS_ACTIVE
        ])->one();

        if (!$emailItem)
            throw new BadRequestHttpException(Yii::t('mailing', 'Email not found'));

        $emailItem->status = MailingListItem::STATUS_UNSUBSCRIBED;
        $emailItem->save(true, ['status']);
    }


    public static function hash(string $email, int $list_id)
    {
        return md5(self::SALT . $email . self::SALT . $list_id . self::SALT);
    }

    public static function checkHash(string $email, int $list_id, string $hash)
    {
        return $hash == self::hash($email, $list_id);
    }


    public static function makeUrl(string $email, int $list_id): string
    {
        if (!$list_id)
            return "";
        $hash = self::hash($email, $list_id);
        return Yii::$app->getModule('mailing')->domain .
            Yii::$app->getModule('mailing')->unsubscribeRoute
            . '?list_id=' . $list_id
            . '&email=' . $email
            . '&hash=' . $hash;
    }

}