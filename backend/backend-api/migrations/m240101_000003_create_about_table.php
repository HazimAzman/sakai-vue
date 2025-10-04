<?php

use yii\db\Migration;

/**
 * Class m240101_000003_create_about_table
 */
class m240101_000003_create_about_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('about', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('About section title'),
            'content' => $this->text()->notNull()->comment('About content'),
            'ceo_name' => $this->string(255)->notNull()->comment('CEO name'),
            'ceo_title' => $this->string(255)->notNull()->comment('CEO title'),
            'ceo_image' => $this->string()->notNull()->comment('CEO image path'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('about');
    }
}
