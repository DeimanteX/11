<?php

namespace app\widgets\UserBalance;

use Yii;
use yii\base\Widget;

/**
 * Виджет баланса пользователя
 * Class UserBalance
 * @package app\widgets\UserBalance
 */
class UserBalance extends Widget
{

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return ;
        }

        return $this->render('user_balance.twig', [
            'user' => Yii::$app->user->identity
        ]);
    }
}