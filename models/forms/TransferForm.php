<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Модель формы перевода средств между пользователями
 * Class TransferForm
 * @package app\models\forms
 */
class TransferForm extends Model
{
    public $amount;
    public $reciever_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'number', 'min' => 0.01, 'max' => 99999999.99],
            [['amount', 'reciever_id'], 'required'],
            ['amount', 'validateTransferSum']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'amount' => 'Сумма перевода ($)',
            'reciever_id' => 'Получатель'
        ];
    }

    public function validateTransferSum($attribute)
    {
        if (!$this->hasErrors()) {

            $sender = Yii::$app->user->identity;

            if ($sender->balance < $this->$attribute) {
                $this->addError($attribute, 'Недостаточно средств для перевода.');
            }
        }
    }
}