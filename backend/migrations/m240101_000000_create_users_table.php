<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m240101_000000_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'role' => $this->string(20)->notNull()->defaultValue('user'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'last_login_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add indexes for better performance
        $this->createIndex('idx-users-username', '{{%users}}', 'username');
        $this->createIndex('idx-users-email', '{{%users}}', 'email');
        $this->createIndex('idx-users-role', '{{%users}}', 'role');
        $this->createIndex('idx-users-status', '{{%users}}', 'status');
        $this->createIndex('idx-users-auth_key', '{{%users}}', 'auth_key');

        // Insert default admin user
        $this->insert('{{%users}}', [
            'username' => 'admin',
            'email' => 'admin@sakai-vue.com',
            'password_hash' => password_hash('Admin@123456', PASSWORD_ARGON2ID),
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'role' => 'admin',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
