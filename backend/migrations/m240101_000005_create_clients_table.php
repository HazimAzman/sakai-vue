<?php

use yii\db\Migration;

/**
 * Class m240101_000005_create_clients_table
 */
class m240101_000005_create_clients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('clients', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Client name'),
            'short_name' => $this->string(255)->notNull()->comment('Client short name'),
            'logo_path' => $this->string()->notNull()->comment('Client logo path'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('clients');
    }
}
