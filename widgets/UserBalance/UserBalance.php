<?php

namespace app\widgets\UserBalance;

use Yii;
use yii\base\Widget;


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