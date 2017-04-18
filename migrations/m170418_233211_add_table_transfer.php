<?php

use yii\db\Migration;

class m170418_233211_add_table_transfer extends Migration
{
    public function safeUp()
    {
        $this->createTable('transfer', [
            'id' => $this->primaryKey(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'reciever_id' => $this->integer()->notNull(),
            'date_create' => $this->dateTime() . '  DEFAULT NOW()'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('transfer');
    }
}
