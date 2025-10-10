<?php

namespace app\services;

use Yii;
use yii\base\Component;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;
use app\models\AuthToken;

class SecurityService extends Component
{
    public $jwtSecret;
    public $encryptionKey;
    public $rateLimit;
    public $rateWindow;
    
    private $rateLimitCache = [];
    
    public function init()
    {
        parent::init();
        
        require_once Yii::getAlias('@app/config/env.php');
        
        $this->jwtSecret = \EnvConfig::get('JWT_SECRET_KEY', 'default-secret-key');
        $this->encryptionKey = \EnvConfig::get('ENCRYPTION_KEY', 'default-32-char-encryption-key-here');
        $this->rateLimit = \EnvConfig::getInt('API_RATE_LIMIT', 100);
        $this->rateWindow = \EnvConfig::getInt('API_RATE_WINDOW', 3600);
    }

    private function requireDbTokens(): bool
    {
        return (bool) \EnvConfig::getInt('REQUIRE_DB_TOKENS', 0);
    }

    private function tableExists(string $tableName): bool
    {
        try {
            $schema = Yii::$app->db->schema;
            return (bool) $schema->getTableSchema($tableName, true);
        } catch (\Throwable $e) {
            return false;
        }
    }
    
    /**
     * Generate JWT token
     */
    public function generateToken($userId, $userRole = 'user', $expiration = 3600)
    {
        $payload = [
            'user_id' => $userId,
            'role' => $userRole,
            'iat' => time(),
            'exp' => time() + $expiration,
            'iss' => 'sakai-vue-api',
            'aud' => 'sakai-vue-frontend',
            'jti' => bin2hex(random_bytes(16))
        ];
        
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
    
    /**
     * Validate JWT token
     */
    public function validateToken($token)
    {
        try {
            // Check revoked list before decoding
            if ($this->isTokenRevoked($token)) {
                throw new UnauthorizedHttpException('Token has been revoked');
            }

            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $payload = (array) $decoded;

            // Also verify by jti if present after decode
            if (!empty($payload['jti']) && $this->isJtiRevoked($payload['jti'])) {
                throw new UnauthorizedHttpException('Token has been revoked');
            }
            return $payload;
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Invalid or expired token');
        }
    }

    /**
     * Revoke a token immediately (e.g., on logout)
     */
    public function revokeToken($token)
    {
        $jti = null;
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $payload = (array) $decoded;
            $jti = $payload['jti'] ?? null;
            $exp = $payload['exp'] ?? (time() + 3600);
        } catch (\Exception $e) {
            // If decode fails, still store hash for a short period
            $exp = time() + 3600;
        }

        $this->storeRevokedToken($token, $jti, $exp);

        // Remove from DB if present
        if ($jti) {
            AuthToken::deleteAll(['jti' => $jti]);
        }
    }

    private function getRevokedStorePath()
    {
        return Yii::getAlias('@app/runtime/revoked_tokens.json');
    }

    private function loadRevokedStore()
    {
        $path = $this->getRevokedStorePath();
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return [];
        }
        // Cleanup expired entries
        $now = time();
        $data = array_values(array_filter($data, function($entry) use ($now) {
            return isset($entry['exp']) && $entry['exp'] > $now;
        }));
        return $data;
    }

    private function saveRevokedStore($data)
    {
        $path = $this->getRevokedStorePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $result = @file_put_contents($path, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        if ($result === false) {
            Yii::error('Failed to write revoked tokens store at: ' . $path, 'security');
        }
    }

    private function tokenHash($token)
    {
        return hash('sha256', $token);
    }

    private function storeRevokedToken($token, $jti, $exp)
    {
        $data = $this->loadRevokedStore();
        $data[] = [
            'hash' => $this->tokenHash($token),
            'jti' => $jti,
            'exp' => $exp
        ];
        $this->saveRevokedStore($data);
    }

    private function isTokenRevoked($token)
    {
        $hash = $this->tokenHash(trim((string)$token));
        foreach ($this->loadRevokedStore() as $entry) {
            if (!empty($entry['hash']) && $entry['hash'] === $hash) {
                return true;
            }
        }
        return false;
    }

    private function isJtiRevoked($jti)
    {
        if (!$jti) return false;
        // DB check first when enabled and table exists
        if ($this->requireDbTokens() && $this->tableExists('{{%auth_tokens}}')) {
            if (AuthToken::find()->where(['jti' => $jti])->count() == 0) {
                return true;
            }
        }
        foreach ($this->loadRevokedStore() as $entry) {
            if (!empty($entry['jti']) && $entry['jti'] === $jti) {
                return true;
            }
        }
        return false;
    }

    /**
     * Persist token entry to DB (called on login/refresh)
     */
    public function persistToken($userId, array $payload, string $token)
    {
        if (empty($payload['jti']) || empty($payload['exp'])) {
            return;
        }
        // Only attempt if table exists
        if (!$this->tableExists('{{%auth_tokens}}')) {
            Yii::warning('auth_tokens table missing; skipping token persistence', 'security');
            return;
        }
        try {
            $model = AuthToken::findOne(['jti' => $payload['jti']]);
            if (!$model) {
                $model = new AuthToken();
                $model->jti = $payload['jti'];
                $model->user_id = (int)$userId;
                $model->token_hash = $this->tokenHash($token);
                $model->expires_at = date('Y-m-d H:i:s', (int)$payload['exp']);
                $model->save(false);
            } else {
                $model->expires_at = date('Y-m-d H:i:s', (int)$payload['exp']);
                $model->token_hash = $this->tokenHash($token);
                $model->save(false);
            }
        } catch (\Throwable $e) {
            Yii::error('Failed to persist token: ' . $e->getMessage(), 'security');
        }
    }
    
    /**
     * Check rate limiting
     */
    public function checkRateLimit($identifier, $endpoint = 'default')
    {
        $key = $identifier . ':' . $endpoint;
        $now = time();
        
        // Clean old entries
        if (isset($this->rateLimitCache[$key])) {
            $this->rateLimitCache[$key] = array_filter(
                $this->rateLimitCache[$key],
                function($timestamp) use ($now) {
                    return ($now - $timestamp) < $this->rateWindow;
                }
            );
        } else {
            $this->rateLimitCache[$key] = [];
        }
        
        // Check if limit exceeded
        if (count($this->rateLimitCache[$key]) >= $this->rateLimit) {
            throw new ForbiddenHttpException('Rate limit exceeded. Please try again later.');
        }
        
        // Add current request
        $this->rateLimitCache[$key][] = $now;
        
        return true;
    }
    
    /**
     * Encrypt sensitive data
     */
    public function encrypt($data)
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Decrypt sensitive data
     */
    public function decrypt($encryptedData)
    {
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }
    
    /**
     * Hash password securely
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3,         // 3 threads
        ]);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate secure random string
     */
    public function generateSecureToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Sanitize input data
     */
    public function sanitizeInput($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        
        if (is_string($data)) {
            // Remove null bytes
            $data = str_replace("\0", '', $data);
            
            // Trim whitespace
            $data = trim($data);
            
            // Remove potentially dangerous characters
            $data = preg_replace('/[<>"\']/', '', $data);
            
            return $data;
        }
        
        return $data;
    }
    
    /**
     * Validate email format
     */
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate password strength
     */
    public function validatePasswordStrength($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Log security events
     */
    public function logSecurityEvent($event, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => Yii::$app->request->getUserIP(),
            'user_agent' => Yii::$app->request->getUserAgent(),
            'details' => $details
        ];
        
        Yii::error(json_encode($logData), 'security');
    }
}
