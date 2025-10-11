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
use yii\web\UnauthorizedHttpException;

class UploadController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:3000', 'http://127.0.0.1:3000', 'http://localhost:5173', 'http://127.0.0.1:5173'],
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
                'image' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    // Handle CORS headers for all requests
    public function beforeAction($action)
    {
        // Set CORS headers for all requests
        $response = Yii::$app->response;
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
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
     * Upload image file
     */
    public function actionImage()
    {
        $uploadedFile = UploadedFile::getInstanceByName('image');
        
        if (!$uploadedFile) {
            throw new BadRequestHttpException('No image file provided');
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($uploadedFile->type, $allowedTypes)) {
            throw new BadRequestHttpException('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        }

        // Validate file size (5MB max)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($uploadedFile->size > $maxSize) {
            throw new BadRequestHttpException('File size too large. Maximum size is 5MB.');
        }

        // Get category from request (default to 'activities' for backward compatibility)
        $category = Yii::$app->request->post('category', 'activities');
        $allowedCategories = ['activities', 'about', 'clients', 'institut', 'service', 'product'];
        
        if (!in_array($category, $allowedCategories)) {
            throw new BadRequestHttpException('Invalid category. Allowed categories: ' . implode(', ', $allowedCategories));
        }

        // Create upload directory if it doesn't exist
        // Store in the main project's public folder (not backend's public folder)
        $uploadDir = dirname(Yii::getAlias('@app')) . '/public/images/' . $category . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = $uploadedFile->extension;
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filePath = $uploadDir . $filename;

        // Save the file
        if ($uploadedFile->saveAs($filePath)) {
            // Return the relative URL path without /public/ prefix for Vite
            $relativePath = '/images/' . $category . '/' . $filename;
            
            return [
                'success' => true,
                'message' => 'Image uploaded successfully',
                'path' => $relativePath,
                'url' => $relativePath, // For compatibility
                'filename' => $filename,
                'size' => $uploadedFile->size,
                'type' => $uploadedFile->type,
                'category' => $category
            ];
        } else {
            // Log detailed error information
            $error = error_get_last();
            Yii::error('File upload failed: ' . ($error['message'] ?? 'Unknown error'), __METHOD__);
            Yii::error('Upload directory: ' . $uploadDir, __METHOD__);
            Yii::error('File path: ' . $filePath, __METHOD__);
            Yii::error('Directory exists: ' . (is_dir($uploadDir) ? 'yes' : 'no'), __METHOD__);
            Yii::error('Directory writable: ' . (is_writable($uploadDir) ? 'yes' : 'no'), __METHOD__);
            
            throw new BadRequestHttpException('Failed to save uploaded file. Check server logs for details.');
        }
    }
}
