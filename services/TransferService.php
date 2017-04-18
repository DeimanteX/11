<?php

namespace app\services;

use Yii;
use app\models\db\User;
use app\models\db\Transfer;

/**
 * Сервис работы с переводами
 * Class TransferService
 * @package app\services
 */
class TransferService
{
    /**
     * Перевод денег между пользователями
     * @param int $senderId
     * @param int $receiverId
     * @param int $amount
     * @throws NotEnoughMoneyException недостаточно средств на счете
     * @throws TransferException непредвиденная ошибка
     * @throws UserNotFoundException отправитель или получатель перевода не найден по id
     */
    public function send($senderId, $receiverId, $amount)
    {
        $sender = User::findOne($senderId);
        $reciever = User::findOne($receiverId);

        if (!$sender || !$reciever) {
            throw new UserNotFoundException();
        }

        if ($sender->balance < $amount) {
            throw new NotEnoughMoneyException();
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $sender->balance -= $amount;
            $reciever->balance += $amount;

            $transfer = new Transfer();
            $transfer->sender_id = $sender->id;
            $transfer->reciever_id = $reciever->id;

            $sender->save();
            $reciever->save();
            $transfer->save();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new TransferException($e->getMessage());
        }
    }
}

/**
 * Нештатная ситуация, внутренняя ошибка сервиса
 * Class TransferException
 * @package app\services
 */
class TransferException extends \Exception {}

/**
 * Недостаточно средств
 * Class NotEnoughMoneyException
 * @package app\services
 */
class NotEnoughMoneyException extends \Exception {}