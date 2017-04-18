<?php

namespace app\services;

use Yii;
use app\models\db\User;
use app\models\db\Transfer;

/**
 * ������ ������ � ����������
 * Class TransferService
 * @package app\services
 */
class TransferService
{
    /**
     * ������� ����� ����� ��������������
     * @param int $senderId
     * @param int $receiverId
     * @param int $amount
     * @throws NotEnoughMoneyException ������������ ������� �� �����
     * @throws TransferException �������������� ������
     * @throws UserNotFoundException ����������� ��� ���������� �������� �� ������ �� id
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
 * ��������� ��������, ���������� ������ �������
 * Class TransferException
 * @package app\services
 */
class TransferException extends \Exception {}

/**
 * ������������ �������
 * Class NotEnoughMoneyException
 * @package app\services
 */
class NotEnoughMoneyException extends \Exception {}