<?php

use yii\db\Migration;

/**
 * Class m240101_000006_create_contacts_table
 */
class m240101_000006_create_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contacts', [
            'id' => $this->primaryKey(),
            'office_name' => $this->string(255)->notNull()->comment('Office name'),
            'address' => $this->text()->notNull()->comment('Office address'),
            'phone' => $this->string()->notNull()->comment('Phone number'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('contacts');
    }
}
