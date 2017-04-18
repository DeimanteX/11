<?php

namespace app\services;

use Yii;
use app\models\db\User;

/**
 * Сервис работы с пользователями
 * Class UserService
 * @package app\services
 */
class UserService
{
    /**
     * Авторизация текущего пользователя
     * @param string $name
     * @param string $password
     * @param bool $rememberMe
     * @return bool true - если пользователь залогинился успешно
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
     * Создание нового пользователя
     * @return bool - если пользователь создался
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
     * Возвращает всех пользователей кроме $excludeId
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
 * Неверный пароль
 * Class InvalidPasswordException
 * @package app\services
 */
class InvalidPasswordException extends \Exception {}

/**
 * Пользователь не найден
 * Class UserNotFoundException
 * @package app\services
 */
class UserNotFoundException extends \Exception {}