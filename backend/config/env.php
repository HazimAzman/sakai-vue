<?php

// Environment configuration loader
class EnvConfig
{
    private static $config = [];
    
    public static function load($file = null)
    {
        $envFile = $file ?: __DIR__ . '/../.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue; // Skip comments
                }
                
                list($name, $value) = explode('=', $line, 2);
                self::$config[trim($name)] = trim($value);
            }
        }
        
        // Set default values
        self::$config = array_merge([
            'DB_HOST' => 'localhost',
            'DB_NAME' => 'sakai_vue_api',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
            'DB_CHARSET' => 'utf8mb4',
            'JWT_SECRET_KEY' => 'default-secret-key-change-in-production',
            'ENCRYPTION_KEY' => 'default-32-char-encryption-key-here',
            'API_RATE_LIMIT' => '100',
            'API_RATE_WINDOW' => '3600',
            'APP_DEBUG' => 'false',
            'APP_ENV' => 'production',
            'CORS_ORIGINS' => 'http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173',
            'LOG_LEVEL' => 'error'
        ], self::$config);
    }
    
    public static function get($key, $default = null)
    {
        return isset(self::$config[$key]) ? self::$config[$key] : $default;
    }
    
    public static function getBool($key, $default = false)
    {
        $value = self::get($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
    public static function getInt($key, $default = 0)
    {
        $value = self::get($key, $default);
        return (int) $value;
    }
}

// Load environment configuration
EnvConfig::load();
