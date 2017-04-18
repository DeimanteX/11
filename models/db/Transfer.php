<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "transfer".
 *
 * @property integer $id
 * @property string $amount
 * @property integer $sender_id
 * @property integer $reciever_id
 * @property string $date_create
 */
class Transfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'sender_id', 'reciever_id'], 'required'],
            [['amount'], 'number'],
            [['sender_id', 'reciever_id'], 'integer'],
            [['date_create'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Sum',
            'sender_id' => 'Sender ID',
            'reciever_id' => 'Reciever ID',
            'date_create' => 'Date Create',
        ];
    }
}
