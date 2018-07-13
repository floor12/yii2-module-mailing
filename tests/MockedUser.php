<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.07.2018
 * Time: 19:07
 */

namespace floor12\mailing\tests;


use yii\base\Model;
use yii\web\IdentityInterface;

class MockedUser extends Model implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return new MockedUser();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return new MockedUser();
    }

    public function getId()
    {
        return 1;
    }


    public function getAuthKey()
    {
        return '1';
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
}