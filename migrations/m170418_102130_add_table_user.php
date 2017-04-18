<?php

use yii\db\Migration;

class m170418_102130_add_table_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'balance' => $this->decimal(10, 2)->notNull(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(200),
            'date_create' => $this->dateTime() . ' DEFAULT NOW()'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
