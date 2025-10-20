<?php

namespace app\filters;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use app\services\SecurityService;

class SecurityFilter extends ActionFilter
{
    public $requireAuth = true;
    public $requireRole = null;
    public $requirePermissionLevel = null;
    public $rateLimit = true;
    public $rateLimitEndpoint = 'default';
    public $validateInput = true;
    public $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function beforeAction($action)
    {
        $securityService = new SecurityService();
        
        // Check allowed HTTP methods
        if (!in_array(Yii::$app->request->method, $this->allowedMethods)) {
            throw new BadRequestHttpException('Method not allowed');
        }

        // Rate limiting
        if ($this->rateLimit) {
            $identifier = Yii::$app->request->getUserIP();
            $securityService->checkRateLimit($identifier, $this->rateLimitEndpoint);
        }

        // Input validation
        if ($this->validateInput) {
            $this->validateInputData();
        }

        // Authentication
        if ($this->requireAuth) {
            $user = $this->getCurrentUser();
            
            // Role-based authorization
            if ($this->requireRole && !$user->hasRole($this->requireRole)) {
                $securityService->logSecurityEvent('access_denied', [
                    'user_id' => $user->id,
                    'required_role' => $this->requireRole,
                    'user_role' => $user->role,
                    'action' => $action->id,
                    'controller' => $action->controller->id,
                    'ip' => Yii::$app->request->getUserIP()
                ]);
                throw new ForbiddenHttpException('Insufficient permissions');
            }

            // Permission level authorization
            if ($this->requirePermissionLevel && !$user->hasPermissionLevel($this->requirePermissionLevel)) {
                $securityService->logSecurityEvent('access_denied', [
                    'user_id' => $user->id,
                    'required_level' => $this->requirePermissionLevel,
                    'user_level' => $user->getRoleHierarchy(),
                    'action' => $action->id,
                    'controller' => $action->controller->id,
                    'ip' => Yii::$app->request->getUserIP()
                ]);
                throw new ForbiddenHttpException('Insufficient permission level');
            }
        }

        return parent::beforeAction($action);
    }

    private function getCurrentUser()
    {
        $securityService = new SecurityService();
        $token = $securityService->getBearerToken();
        
        if (!$token) {
            throw new UnauthorizedHttpException('Authorization token required');
        }
        
        // Use findIdentityByAccessToken which validates token against database
        $user = \app\models\User::findIdentityByAccessToken($token);
        
        if (!$user) {
            throw new UnauthorizedHttpException('Invalid or expired token');
        }
        
        return $user;
    }

    private function validateInputData()
    {
        $securityService = new SecurityService();
        
        // Validate Content-Type for POST/PUT/PATCH requests
        if (in_array(Yii::$app->request->method, ['POST', 'PUT', 'PATCH'])) {
            $contentType = Yii::$app->request->getContentType();
            if ($contentType !== 'application/json') {
                throw new BadRequestHttpException('Content-Type must be application/json');
            }
        }

        // Validate JSON data
        if (in_array(Yii::$app->request->method, ['POST', 'PUT', 'PATCH'])) {
            $rawBody = Yii::$app->request->getRawBody();
            $data = json_decode($rawBody, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new BadRequestHttpException('Invalid JSON data: ' . json_last_error_msg());
            }

            // Sanitize input data
            if ($data) {
                $sanitizedData = $securityService->sanitizeInput($data);
                // Store sanitized data for use in controller
                Yii::$app->request->setBodyParams($sanitizedData);
            }
        }

        // Validate request size
        $contentLength = Yii::$app->request->getHeaders()->get('Content-Length');
        if ($contentLength && $contentLength > 1024 * 1024) { // 1MB limit
            throw new BadRequestHttpException('Request too large');
        }
    }
}
