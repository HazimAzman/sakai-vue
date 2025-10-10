<?php

use yii\db\Migration;

/**
 * Class m240101_000001_update_products_table
 */
class m240101_000001_update_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop the price column and add new columns
        $this->dropColumn('products', 'price');
        $this->addColumn('products', 'image_path', $this->string()->notNull()->comment('Image path for the product'));
        $this->addColumn('products', 'category', $this->string()->notNull()->comment('Product category'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Revert changes
        $this->dropColumn('products', 'image_path');
        $this->dropColumn('products', 'category');
        $this->addColumn('products', 'price', $this->decimal(10,2)->comment('Product price'));
    }
}
