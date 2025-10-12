<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class UploadController extends Controller
{
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
                'image' => ['POST', 'OPTIONS'],
            ],
        ];

        return $behaviors;
    }

    // Handle CORS headers for all requests
    public function beforeAction($action)
    {
        // Set CORS headers for all requests
        $response = Yii::$app->response;
        $allowedOrigins = $this->getCorsOrigins();
        $origin = Yii::$app->request->headers->get('Origin');
        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
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

    private function getCorsOrigins()
    {
        require_once Yii::getAlias('@app/config/env.php');
        $origins = \EnvConfig::get('CORS_ORIGINS', 'http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173');
        return explode(',', $origins);
    }

    public function actions()
    {
        $actions = parent::actions();
        
        // Remove default actions that we want to override
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    /**
     * Upload image file
     */
    public function actionImage()
    {
        try {
            // Debug: Log request method and content type
            Yii::info('Upload request method: ' . Yii::$app->request->method, 'upload');
            Yii::info('Content type: ' . Yii::$app->request->getContentType(), 'upload');
            Yii::info('POST data: ' . print_r($_POST, true), 'upload');
            Yii::info('FILES data: ' . print_r($_FILES, true), 'upload');

            // Get category from request (default to 'activities' for backward compatibility)
            $category = Yii::$app->request->post('category', 'activities');
            $allowedCategories = ['activities', 'about', 'clients', 'institut', 'service', 'product'];
            
            if (!in_array($category, $allowedCategories)) {
                throw new BadRequestHttpException('Invalid category. Allowed categories: ' . implode(', ', $allowedCategories));
            }

            // Check if file was uploaded
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = 'No image file uploaded or upload error';
                if (isset($_FILES['image']['error'])) {
                    $errorMsg .= ' (Error code: ' . $_FILES['image']['error'] . ')';
                }
                throw new BadRequestHttpException($errorMsg);
            }

            $file = $_FILES['image'];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $file['type'];
            if (!in_array($fileType, $allowedTypes)) {
                throw new BadRequestHttpException('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes) . '. Got: ' . $fileType);
            }

            // Validate file size (5MB max)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                throw new BadRequestHttpException('File too large. Maximum size: 5MB');
            }

            // Create upload directory
            $uploadDir = dirname(Yii::getAlias('@app')) . '/public/images/' . $category . '/';
            
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new BadRequestHttpException('Failed to create upload directory: ' . $uploadDir);
                }
            }

            if (!is_writable($uploadDir)) {
                throw new BadRequestHttpException('Upload directory is not writable: ' . $uploadDir);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $filename;

            // Save file using move_uploaded_file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                Yii::error('Failed to save uploaded file', 'upload');
                Yii::error('Directory exists: ' . (is_dir($uploadDir) ? 'yes' : 'no'), 'upload');
                Yii::error('Directory writable: ' . (is_writable($uploadDir) ? 'yes' : 'no'), 'upload');
                Yii::error('File path: ' . $filePath, 'upload');
                Yii::error('Source file: ' . $file['tmp_name'], 'upload');
                throw new BadRequestHttpException('Failed to save uploaded file');
            }

            // Return success response with file path
            $relativePath = '/images/' . $category . '/' . $filename;
            
            return [
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'path' => $relativePath,
                    'full_path' => $filePath,
                    'size' => $file['size'],
                    'type' => $fileType,
                    'category' => $category
                ]
            ];

        } catch (\Exception $e) {
            Yii::error('Upload error: ' . $e->getMessage(), 'upload');
            Yii::error('Stack trace: ' . $e->getTraceAsString(), 'upload');
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }
}
