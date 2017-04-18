<?php

namespace app\widgets\SendTransfer;

use app\services\UserService;
use Yii;
use yii\base\Widget;
use app\models\forms\TransferForm;
use yii\helpers\ArrayHelper;

/**
 * Виджет формы перевода средств
 * Class SendTransfer
 * @package app\widgets\SendTransfer
 */
class SendTransfer extends Widget
{
    private $userService;

    public function __construct(UserService $userService, $config = [])
    {
        parent::__construct($config);
        $this->userService = $userService;
    }

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return ;
        }

        $recievers = $this->userService->getUsers(Yii::$app->user->id);

        return $this->render('send_transfer.twig', [
            'model' => new TransferForm(),
            'recievers' => ArrayHelper::map($recievers, 'id', 'name')
        ]);
    }
}