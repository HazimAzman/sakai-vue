<?php

use yii\db\Migration;

/**
 * Class m240101_000004_create_downloads_table
 */
class m240101_000004_create_downloads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('downloads', [
            'id' => $this->primaryKey(),
            'brand_name' => $this->string(255)->notNull()->comment('Brand name'),
            'download_url' => $this->string()->notNull()->comment('Download URL'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('downloads');
    }
}
