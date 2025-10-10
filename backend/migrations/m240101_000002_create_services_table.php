<?php

use yii\db\Migration;

/**
 * Class m240101_000002_create_services_table
 */
class m240101_000002_create_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('services', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Service title'),
            'description' => $this->text()->notNull()->comment('Service description'),
            'image_path' => $this->string()->notNull()->comment('Service image path'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('services');
    }
}
