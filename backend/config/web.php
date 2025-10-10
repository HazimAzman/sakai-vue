<?php

require_once __DIR__ . '/env.php';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'sakai-vue-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'security'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => EnvConfig::get('COOKIE_VALIDATION_KEY', 'nzkEd0cuGqgEZVDQ_-mfJAXFPBf5Bort'),
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'security' => [
            'class' => 'app\services\SecurityService',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false, // Disable for security
            'enableSession' => false,   // Use stateless authentication
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                
                // Add security headers
                $response->headers->set('X-Content-Type-Options', 'nosniff');
                $response->headers->set('X-Frame-Options', 'DENY');
                $response->headers->set('X-XSS-Protection', '1; mode=block');
                $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
                $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
                
                // Add HSTS header for HTTPS
                if (Yii::$app->request->isSecureConnection) {
                    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
                }
                
                // Content Security Policy
                $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");
            },
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // API Health and Info
                'api' => 'api/index',
                'api/health' => 'api/health',
                
                // Authentication endpoints
                'api/auth/login' => 'auth/login',
                'api/auth/register' => 'auth/register',
                'api/auth/logout' => 'auth/logout',
                'api/auth/refresh' => 'auth/refresh',
                'api/auth/profile' => 'auth/profile',
                'api/auth/change-password' => 'auth/change-password',
                
                // Products API
                'api/products' => 'product/index',
                'api/products/<id:\d+>' => 'product/view',
                'api/products/create' => 'product/create',
                'api/products/<id:\d+>/update' => 'product/update',
                'api/products/<id:\d+>/delete' => 'product/delete',
                
                // Services API
                'api/services' => 'service/index',
                'api/services/<id:\d+>' => 'service/view',
                'api/services/create' => 'service/create',
                'api/services/<id:\d+>/update' => 'service/update',
                'api/services/<id:\d+>/delete' => 'service/delete',
                
                // About API
                'api/about' => 'about/index',
                'api/about/<id:\d+>' => 'about/view',
                'api/about/create' => 'about/create',
                'api/about/<id:\d+>/update' => 'about/update',
                'api/about/<id:\d+>/delete' => 'about/delete',
                
                // Downloads API
                'api/downloads' => 'download/index',
                'api/downloads/<id:\d+>' => 'download/view',
                'api/downloads/create' => 'download/create',
                'api/downloads/<id:\d+>/update' => 'download/update',
                'api/downloads/<id:\d+>/delete' => 'download/delete',
                
                // Clients API
                'api/clients' => 'client/index',
                'api/clients/<id:\d+>' => 'client/view',
                'api/clients/create' => 'client/create',
                'api/clients/<id:\d+>/update' => 'client/update',
                'api/clients/<id:\d+>/delete' => 'client/delete',
                
               // Contacts API
               'api/contacts' => 'contact/index',
               'api/contacts/<id:\d+>' => 'contact/view',
               'api/contacts/create' => 'contact/create',
               'api/contacts/<id:\d+>/update' => 'contact/update',
               'api/contacts/<id:\d+>/delete' => 'contact/delete',

               // Institutes API
               'api/institutes' => 'institute/index',
               'api/institutes/<id:\d+>' => 'institute/view',
               'api/institutes/create' => 'institute/create',
               'api/institutes/<id:\d+>/update' => 'institute/update',
               'api/institutes/<id:\d+>/delete' => 'institute/delete',

               // Activities API
               'api/activities' => 'activity/index',
               'api/activities/<id:\d+>' => 'activity/view',
               'api/activities/create' => 'activity/create',
               'api/activities/<id:\d+>/update' => 'activity/update',
               'api/activities/<id:\d+>/delete' => 'activity/delete',
           ],
        ],
    ],
    'params' => $params,
];

// Only enable debug and gii in development environment
if (EnvConfig::getBool('APP_DEBUG', false) && EnvConfig::get('APP_ENV', 'production') === 'development') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '10.*'], // Restrict access
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '10.*'], // Restrict access
    ];
}

return $config;
