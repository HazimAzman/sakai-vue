<?php

namespace app\middleware;

use Yii;
use yii\base\ActionFilter;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use app\services\SecurityService;

class SecurityMiddleware extends ActionFilter
{
    public $maxRequestSize = 1048576; // 1MB
    public $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    public $maxFileSize = 5242880; // 5MB

    public function beforeAction($action)
    {
        $this->validateRequestSize();
        $this->validateHeaders();
        $this->validateUserAgent();
        $this->logRequest();
        
        return parent::beforeAction($action);
    }

    private function validateRequestSize()
    {
        $contentLength = Yii::$app->request->getHeaders()->get('Content-Length');
        
        if ($contentLength && $contentLength > $this->maxRequestSize) {
            throw new BadRequestHttpException('Request too large');
        }
    }

    private function validateHeaders()
    {
        $headers = Yii::$app->request->getHeaders();
        
        // Check for suspicious headers
        $suspiciousHeaders = [
            'X-Forwarded-For' => '/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/',
            'X-Real-IP' => '/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/',
        ];

        foreach ($suspiciousHeaders as $header => $pattern) {
            $value = $headers->get($header);
            if ($value && !preg_match($pattern, $value)) {
                $this->logSecurityEvent('suspicious_header', [
                    'header' => $header,
                    'value' => $value,
                    'ip' => Yii::$app->request->getUserIP()
                ]);
            }
        }
    }

    private function validateUserAgent()
    {
        $userAgent = Yii::$app->request->getUserAgent();
        
        // Check for suspicious user agents
        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python/i',
            '/php/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $this->logSecurityEvent('suspicious_user_agent', [
                    'user_agent' => $userAgent,
                    'ip' => Yii::$app->request->getUserIP()
                ]);
            }
        }
    }

    private function logRequest()
    {
        $securityService = new SecurityService();
        
        $securityService->logSecurityEvent('api_request', [
            'method' => Yii::$app->request->method,
            'url' => Yii::$app->request->url,
            'ip' => Yii::$app->request->getUserIP(),
            'user_agent' => Yii::$app->request->getUserAgent(),
            'referer' => Yii::$app->request->getReferrer(),
        ]);
    }

    private function logSecurityEvent($event, $details = [])
    {
        $securityService = new SecurityService();
        $securityService->logSecurityEvent($event, $details);
    }
}
