<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17.07.2018
 * Time: 12:55
 */

namespace floor12\mailing\tests;


use floor12\mailing\interfaces\MailingRecipientInterface;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements MailingRecipientInterface, IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    /** Возвращает массив для формирования dropdown листа выбора получатетелй в форме редактирования рассылок
     * @return array
     */
    public static function getMailingList(): array
    {
        return [];
    }

    /** Возращаем адекватное название модели чтобы выводить его в форме редактирования рассылок
     * @return string
     */
    public static function getMailingLabel(): string
    {
        return "Пользователи";
    }

    public static function findIdentity($id)
    {
        return 1;
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(1);
    }

    /** Возращаем строку содержащую email адресс для отправки рассылки.
     *  Если в модели есть поле email, то реализация такая:
     *
     *      public function getMailingEmail(): string
     *      {
     *          return $this->email;
     *      }
     *
     * @return string
     */
    public function getMailingEmail(): string
    {
        return $this->user_email;
    }


    /** Возращаем строку содержащую полное имя получателя для рассылки.
     *  Если в модели есть поле name и surname, то реализация может выглядить так:
     *
     *      public function getMailingFullname(): string
     *      {
     *          return "{$this->name} {$this->surname}";
     *      }
     *
     * @return string
     */
    public function getMailingFullname(): string
    {
        return $this->user_name;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return "dsfsdfsdf";
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }


}