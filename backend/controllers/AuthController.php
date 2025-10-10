<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use app\models\User;
use app\services\SecurityService;

class AuthController extends Controller
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => $this->getCorsOrigins(),
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // Add content negotiator
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // Add verb filter
        $behaviors['verbFilter'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'login' => ['POST'],
                'register' => ['POST'],
                'logout' => ['POST'],
                'refresh' => ['POST'],
                'profile' => ['GET', 'PUT'],
                'change-password' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    private function getCorsOrigins()
    {
        require_once Yii::getAlias('@app/config/env.php');
        $origins = \EnvConfig::get('CORS_ORIGINS', 'http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173');
        return explode(',', $origins);
    }

    public function beforeAction($action)
    {
        $securityService = new SecurityService();
        
        // Check rate limiting for all actions except profile
        if ($action->id !== 'profile') {
            $identifier = Yii::$app->request->getUserIP();
            $securityService->checkRateLimit($identifier, 'auth');
        }

        // Set CORS headers
        $response = Yii::$app->response;
        $requestOrigin = Yii::$app->request->getHeaders()->get('Origin');
        $allowedOrigins = $this->getCorsOrigins();
        if ($requestOrigin && in_array($requestOrigin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $requestOrigin);
        }
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '86400');
        
        if (Yii::$app->request->isOptions) {
            $response->statusCode = 200;
            $response->format = Response::FORMAT_JSON;
            $response->data = [];
            return false;
        }

        return parent::beforeAction($action);
    }

    /**
     * User login
     */
    public function actionLogin()
    {
        $securityService = new SecurityService();
        
        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        
        if (!$data || !isset($data['username']) || !isset($data['password'])) {
            $securityService->logSecurityEvent('login_failed', ['reason' => 'missing_credentials']);
            throw new BadRequestHttpException('Username and password are required');
        }

        // Sanitize input
        $username = $securityService->sanitizeInput($data['username']);
        $password = $data['password'];

        // Find user by username or email
        $user = User::findByUsername($username) ?: User::findByEmail($username);
        
        if (!$user || !$user->validatePassword($password)) {
            $securityService->logSecurityEvent('login_failed', [
                'username' => $username,
                'ip' => Yii::$app->request->getUserIP()
            ]);
            throw new UnauthorizedHttpException('Invalid username or password');
        }

        // Check if user is active
        if ($user->status !== User::STATUS_ACTIVE) {
            $securityService->logSecurityEvent('login_failed', [
                'username' => $username,
                'reason' => 'account_inactive',
                'ip' => Yii::$app->request->getUserIP()
            ]);
            throw new ForbiddenHttpException('Account is inactive or banned');
        }

        // Generate JWT token and persist DB entry
        $token = $securityService->generateToken($user->id, $user->role, 3600); // 1 hour
        try {
            $payload = $securityService->validateToken($token);
            $securityService->persistToken($user->id, $payload, $token);
        } catch (\Throwable $e) {
            // Only deny login if DB-backed tokens are strictly required
            require_once Yii::getAlias('@app/config/env.php');
            $requireDb = (bool) \EnvConfig::getInt('REQUIRE_DB_TOKENS', 0);
            if ($requireDb) {
                throw new UnauthorizedHttpException('Failed to issue token');
            }
            // Otherwise proceed without DB persistence
        }
        
        // Update last login
        $user->updateLastLogin();
        
        // Log successful login
        $securityService->logSecurityEvent('login_success', [
            'user_id' => $user->id,
            'username' => $user->username,
            'ip' => Yii::$app->request->getUserIP()
        ]);

        return [
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'last_login' => $user->last_login_at
                ]
            ]
        ];
    }

    /**
     * User registration
     */
    public function actionRegister()
    {
        $securityService = new SecurityService();
        
        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        
        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON data');
        }

        // Sanitize input
        $data = $securityService->sanitizeInput($data);

        // Validate required fields
        $requiredFields = ['username', 'email', 'password', 'password_repeat'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new BadRequestHttpException("Field '{$field}' is required");
            }
        }

        // Validate email format
        if (!$securityService->validateEmail($data['email'])) {
            throw new BadRequestHttpException('Invalid email format');
        }

        // Check if user already exists
        if (User::findByUsername($data['username'])) {
            throw new BadRequestHttpException('Username already exists');
        }

        if (User::findByEmail($data['email'])) {
            throw new BadRequestHttpException('Email already exists');
        }

        // Create new user
        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->password_repeat = $data['password_repeat'];
        $user->role = User::ROLE_USER;
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();

        if (!$user->save()) {
            return [
                'success' => false,
                'message' => 'Registration failed',
                'errors' => $user->errors
            ];
        }

        // Log successful registration
        $securityService->logSecurityEvent('user_registered', [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'ip' => Yii::$app->request->getUserIP()
        ]);

        return [
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]
        ];
    }

    /**
     * Get user profile
     */
    public function actionProfile()
    {
        $user = $this->getCurrentUser();
        
        return [
            'success' => true,
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'last_login' => $user->last_login_at,
                'created_at' => $user->created_at
            ]
        ];
    }

    /**
     * Change password
     */
    public function actionChangePassword()
    {
        $user = $this->getCurrentUser();
        $securityService = new SecurityService();
        
        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        
        if (!$data || !isset($data['current_password']) || !isset($data['new_password'])) {
            throw new BadRequestHttpException('Current password and new password are required');
        }

        // Verify current password
        if (!$user->validatePassword($data['current_password'])) {
            $securityService->logSecurityEvent('password_change_failed', [
                'user_id' => $user->id,
                'reason' => 'invalid_current_password',
                'ip' => Yii::$app->request->getUserIP()
            ]);
            throw new UnauthorizedHttpException('Current password is incorrect');
        }

        // Validate new password strength
        $passwordValidation = $securityService->validatePasswordStrength($data['new_password']);
        if ($passwordValidation !== true) {
            return [
                'success' => false,
                'message' => 'Password validation failed',
                'errors' => $passwordValidation
            ];
        }

        // Update password
        $user->password = $data['new_password'];
        if (!$user->save()) {
            return [
                'success' => false,
                'message' => 'Failed to update password',
                'errors' => $user->errors
            ];
        }

        // Log password change
        $securityService->logSecurityEvent('password_changed', [
            'user_id' => $user->id,
            'ip' => Yii::$app->request->getUserIP()
        ]);

        return [
            'success' => true,
            'message' => 'Password changed successfully'
        ];
    }

    /**
     * Logout (invalidate token)
     */
    public function actionLogout()
    {
        $securityService = new SecurityService();

        // Try to get token from Authorization header first
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        $token = null;
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }

        // Fallback: allow token in JSON body (useful if client can’t set headers)
        if (!$token) {
            $rawBody = Yii::$app->request->getRawBody();
            $data = json_decode($rawBody, true);
            if (is_array($data) && !empty($data['token'])) {
                $token = $data['token'];
            }
        }

        if ($token) {
            // Attempt to capture user before revocation for logging
            $userId = null;
            try {
                $payload = $securityService->validateToken($token);
                $userId = $payload['user_id'] ?? null;
            } catch (\Throwable $e) {
                // ignore; proceed with revocation anyway
            }

            // Revoke token
            $securityService->revokeToken($token);

            // Log logout
            $securityService->logSecurityEvent('user_logout', [
                'user_id' => $userId,
                'ip' => Yii::$app->request->getUserIP()
            ]);
        }

        return [
            'success' => true,
            'message' => 'Logout successful'
        ];
    }

    /**
     * Refresh token
     */
    public function actionRefresh()
    {
        $user = $this->getCurrentUser();
        $securityService = new SecurityService();
        
        // Generate new token and persist DB entry
        $token = $securityService->generateToken($user->id, $user->role, 3600);
        try {
            $payload = $securityService->validateToken($token);
            $securityService->persistToken($user->id, $payload, $token);
        } catch (\Throwable $e) {
            require_once Yii::getAlias('@app/config/env.php');
            $requireDb = (bool) \EnvConfig::getInt('REQUIRE_DB_TOKENS', 0);
            if ($requireDb) {
                throw new UnauthorizedHttpException('Failed to refresh token');
            }
        }
        
        return [
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token
            ]
        ];
    }

    /**
     * Get current authenticated user
     */
    private function getCurrentUser()
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            throw new UnauthorizedHttpException('Authorization token required');
        }

        $token = $matches[1];
        $securityService = new SecurityService();
        
        try {
            $payload = $securityService->validateToken($token);
            $user = User::findIdentity($payload['user_id']);
            
            if (!$user) {
                throw new UnauthorizedHttpException('User not found');
            }
            
            return $user;
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Invalid or expired token');
        }
    }
}
