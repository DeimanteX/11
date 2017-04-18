<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\db\User;

/**
 * Модель формы регистрации пользователя
 * Class SignupForm
 * @package app\models\forms
 */
class SignupForm extends Model
{
    public $name;
    public $balance;
    public $password;
    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance'], 'number', 'min' => 0, 'max' => 99999999.99],
            [['name', 'password', 'balance', 'passwordRepeat'], 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],
            ['name', 'unique', 'targetClass' => User::className(), 'message' => 'Это имя пользователя уже занято']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Логин',
            'balance' => 'Сумма на счету ($)',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля'
        ];
    }
}