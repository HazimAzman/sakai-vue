<?php

use yii\db\Migration;

class m240201_000001_create_auth_tokens_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%auth_tokens}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'jti' => $this->string(64)->notNull()->unique(),
            'token_hash' => $this->string(64)->notNull(),
            'expires_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx_auth_tokens_user_id', '{{%auth_tokens}}', 'user_id');
        $this->addForeignKey(
            'fk_auth_tokens_user',
            '{{%auth_tokens}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_auth_tokens_user', '{{%auth_tokens}}');
        $this->dropTable('{{%auth_tokens}}');
    }
}


