<?php

namespace app\services;

use Yii;
use app\models\db\User;

/**
 * —ервис работы с пользовател€ми
 * Class UserService
 * @package app\services
 */
class UserService
{
    /**
     * јвторизаци€ текущего пользовател€
     * @param string $name
     * @param string $password
     * @param bool $rememberMe
     * @return bool true - если пользователь залогинилс€ успешно
     * @throws UserNotFoundException пользователь не найден
     * @throws InvalidPasswordException неверный пароль
     */
    public function login($name, $password, $rememberMe)
    {
        $user = User::findOne(['name' => $name]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!$user->validatePassword($password)) {
            throw new InvalidPasswordException();
        }

        return Yii::$app->user->login($user, $rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * —оздание нового пользовател€
     * @return bool - если пользователь создалс€
     */
    public function signup($name, $password, $balance)
    {
        $user = new User();
        $user->name = $name;
        $user->balance = $balance;
        $user->setPassword($password);
        $user->generateAuthKey();

        return $user->save() ? $user : false;
    }

    /**
     * ¬озвращает всех пользователей кроме $excludeId
     * @param int $excludeId
     * @return array
     */
    public function getUsers($excludeId = 0)
    {
        $query = User::find();

        if ($excludeId) {
            $query->where('id != :id', ['id' => $excludeId]);
        }

        return $query->all();
    }
}

/**
 * Ќеверный пароль
 * Class InvalidPasswordException
 * @package app\services
 */
class InvalidPasswordException extends \Exception {}

/**
 * ѕользователь не найден
 * Class UserNotFoundException
 * @package app\services
 */
class UserNotFoundException extends \Exception {}