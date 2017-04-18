<?php

namespace app\models\forms;

use app\models\db\User;
use app\models\UserIdentity;
use Yii;
use yii\base\Model;

/**
 * Модель формы авторизации
 */
class LoginForm extends Model
{
    public $name;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['name', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        ];
    }

    public function setInvalidPasswordOrLoginError()
    {
        $this->addError('password', 'Неверное имя или пароль.');
    }
}
