<?php

use yii\db\Migration;

/**
 * Handles the creation of table `institutes`.
 */
class m240101_000007_create_institutes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('institutes', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'abbreviation' => $this->string()->notNull(),
            'image_path' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('institutes');
    }
}
