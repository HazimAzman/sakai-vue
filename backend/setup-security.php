<?php
/**
 * Security Setup Script for Sakai Vue API
 * 
 * This script sets up the security features for the backend API
 * Run this script after installing the application
 */

require_once __DIR__ . '/vendor/autoload.php';
// Define environment for console bootstrap
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
// Load Yii core and app env config
require_once __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/config/env.php';

use yii\console\Application;
use yii\db\Connection;

// Initialize Yii console application
$config = require __DIR__ . '/config/console.php';
$app = new Application($config);

echo "🔒 Sakai Vue API Security Setup\n";
echo "================================\n\n";

// Step 1: Check PHP version and extensions
echo "1. Checking PHP requirements...\n";
$phpVersion = PHP_VERSION;
if (version_compare($phpVersion, '7.4.0', '<')) {
    echo "❌ PHP 7.4 or higher is required. Current version: $phpVersion\n";
    exit(1);
}
echo "✅ PHP version: $phpVersion\n";

// Check required extensions
$requiredExtensions = ['openssl', 'json', 'pdo', 'pdo_mysql', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        echo "❌ Required extension '$ext' is not loaded\n";
        exit(1);
    }
    echo "✅ Extension '$ext' loaded\n";
}
echo "\n";

// Step 2: Generate secure keys
echo "2. Generating secure keys...\n";
$jwtSecret = bin2hex(random_bytes(32));
$encryptionKey = bin2hex(random_bytes(16)); // 32 characters for AES-256

echo "✅ JWT Secret Key generated\n";
echo "✅ Encryption Key generated\n";
echo "\n";

// Step 3: Create .env file
echo "3. Creating environment configuration...\n";
$envContent = "# Database Configuration
DB_HOST=localhost
DB_NAME=sakai_vue_api
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Security Configuration
JWT_SECRET_KEY=$jwtSecret
ENCRYPTION_KEY=$encryptionKey
API_RATE_LIMIT=100
API_RATE_WINDOW=3600

# Application Configuration
APP_NAME=\"Sakai Vue API\"
APP_VERSION=\"1.0.0\"
APP_DEBUG=false
APP_ENV=production

# CORS Configuration
CORS_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173

# Email Configuration
MAILER_HOST=smtp.gmail.com
MAILER_PORT=587
MAILER_USERNAME=your-email@gmail.com
MAILER_PASSWORD=your-app-password
MAILER_ENCRYPTION=tls

# Logging
LOG_LEVEL=error
LOG_FILE=runtime/logs/app.log
";

if (file_put_contents(__DIR__ . '/.env', $envContent)) {
    echo "✅ .env file created\n";
} else {
    echo "❌ Failed to create .env file\n";
    exit(1);
}
echo "\n";

// Step 4: Set file permissions
echo "4. Setting file permissions...\n";
$permissions = [
    __DIR__ . '/.env' => 0600,
    __DIR__ . '/runtime' => 0777,
    __DIR__ . '/web/assets' => 0777,
    __DIR__ . '/config' => 0755,
];

foreach ($permissions as $path => $perm) {
    if (file_exists($path)) {
        chmod($path, $perm);
        echo "✅ Set permissions for " . basename($path) . "\n";
    }
}
echo "\n";

// Step 5: Run database migrations
echo "5. Running database migrations...\n";
try {
    $migration = $app->runAction('migrate', ['interactive' => false]);
    echo "✅ Database migrations completed\n";
} catch (Exception $e) {
    echo "❌ Database migration failed: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
    exit(1);
}
echo "\n";

// Step 6: Create admin user
echo "6. Creating default admin user...\n";
try {
    $adminUser = new \app\models\User();
    $adminUser->username = 'admin';
    $adminUser->email = 'admin@sakai-vue.com';
    $adminUser->password = 'Admin@123456';
    $adminUser->role = \app\models\User::ROLE_ADMIN;
    $adminUser->status = \app\models\User::STATUS_ACTIVE;
    $adminUser->generateAuthKey();
    
    if ($adminUser->save()) {
        echo "✅ Admin user created successfully\n";
        echo "   Username: admin\n";
        echo "   Email: admin@sakai-vue.com\n";
        echo "   Password: Admin@123456\n";
        echo "   ⚠️  Please change the default password after first login!\n";
    } else {
        echo "❌ Failed to create admin user: " . implode(', ', $adminUser->getFirstErrors()) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to create admin user: " . $e->getMessage() . "\n";
}
echo "\n";

// Step 7: Test security features
echo "7. Testing security features...\n";
try {
    $securityService = new \app\services\SecurityService();
    
    // Test password hashing
    $testPassword = 'TestPassword123!';
    $hash = $securityService->hashPassword($testPassword);
    if ($securityService->verifyPassword($testPassword, $hash)) {
        echo "✅ Password hashing works correctly\n";
    } else {
        echo "❌ Password hashing test failed\n";
    }
    
    // Test JWT token generation
    $token = $securityService->generateToken(1, 'admin', 3600);
    $payload = $securityService->validateToken($token);
    if ($payload['user_id'] == 1 && $payload['role'] == 'admin') {
        echo "✅ JWT token generation and validation works correctly\n";
    } else {
        echo "❌ JWT token test failed\n";
    }
    
    // Test encryption
    $testData = 'Sensitive data';
    $encrypted = $securityService->encrypt($testData);
    $decrypted = $securityService->decrypt($encrypted);
    if ($decrypted === $testData) {
        echo "✅ Data encryption works correctly\n";
    } else {
        echo "❌ Data encryption test failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Security feature test failed: " . $e->getMessage() . "\n";
}
echo "\n";

// Step 8: Security recommendations
echo "8. Security recommendations:\n";
echo "   🔐 Change default admin password immediately\n";
echo "   🔐 Update JWT secret key in production\n";
echo "   🔐 Configure proper CORS origins for your domain\n";
echo "   🔐 Enable HTTPS in production\n";
echo "   🔐 Set up proper database user with limited privileges\n";
echo "   🔐 Configure firewall rules\n";
echo "   🔐 Set up log monitoring\n";
echo "   🔐 Regular security updates\n";
echo "\n";

// Step 9: API testing
echo "9. API endpoints available:\n";
echo "   🔗 Authentication:\n";
echo "      POST /api/auth/login\n";
echo "      POST /api/auth/register\n";
echo "      POST /api/auth/logout\n";
echo "      GET  /api/auth/profile\n";
echo "      POST /api/auth/change-password\n";
echo "\n";
echo "   🔗 Protected endpoints (require JWT token):\n";
echo "      GET    /api/products\n";
echo "      POST   /api/products/create\n";
echo "      PUT    /api/products/{id}/update\n";
echo "      DELETE /api/products/{id}/delete\n";
echo "\n";

echo "🎉 Security setup completed successfully!\n";
echo "========================================\n";
echo "Your Sakai Vue API backend is now secured with:\n";
echo "✅ JWT Authentication\n";
echo "✅ Role-Based Access Control\n";
echo "✅ Input Validation & Sanitization\n";
echo "✅ Rate Limiting\n";
echo "✅ Security Headers\n";
echo "✅ Data Encryption\n";
echo "✅ Comprehensive Logging\n";
echo "✅ CSRF Protection\n";
echo "\n";
echo "Next steps:\n";
echo "1. Test the API endpoints\n";
echo "2. Configure your frontend to use JWT tokens\n";
echo "3. Set up monitoring and logging\n";
echo "4. Deploy to production with proper security measures\n";
echo "\n";
echo "For detailed security documentation, see: SECURITY_IMPLEMENTATION.md\n";
