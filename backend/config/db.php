<?php

require_once __DIR__ . '/env.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . EnvConfig::get('DB_HOST', 'localhost') . ';dbname=' . EnvConfig::get('DB_NAME', 'sakai_vue_api') . ';charset=' . EnvConfig::get('DB_CHARSET', 'utf8mb4'),
    'username' => EnvConfig::get('DB_USERNAME', 'root'),
    'password' => EnvConfig::get('DB_PASSWORD', ''),
    'charset' => EnvConfig::get('DB_CHARSET', 'utf8mb4'),

    // Schema cache options (for production environment)
    'enableSchemaCache' => !EnvConfig::getBool('APP_DEBUG', false),
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
    
    // Security options
    'attributes' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
