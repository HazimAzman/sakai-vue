<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use app\models\Product;
use app\filters\SecurityFilter;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';

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

        // Add security filter for write actions only (create, update, delete)
        $behaviors['securityFilter'] = [
            'class' => SecurityFilter::class,
            'only' => ['create', 'update', 'delete'],
            'requireAuth' => true,
            'requirePermissionLevel' => 1,
            'rateLimit' => true,
            'rateLimitEndpoint' => 'products',
        ];

        return $behaviors;
    }

    private function getCorsOrigins()
    {
        require_once Yii::getAlias('@app/config/env.php');
        $origins = \EnvConfig::get('CORS_ORIGINS', 'http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173');
        return explode(',', $origins);
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

    public function actions()
    {
        $actions = parent::actions();
        
        // Remove default actions that we want to override
        unset($actions['update'], $actions['create'], $actions['delete']);
        
        // Customize the data provider preparation with the "prepareDataProvider()" method.
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }

    public function prepareDataProvider()
    {
        return new \yii\data\ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => false, // Disable pagination to return all products
        ]);
    }

    public function actionCreate()
    {
        $model = new Product();
        
        // Get sanitized data from request (already processed by SecurityFilter)
        $data = Yii::$app->request->getBodyParams();
        
        if ($data && $model->load($data, '')) {
            if ($model->save()) {
                return [
                    'success' => true,
                    'message' => 'Product created successfully',
                    'data' => $model
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create product',
                    'errors' => $model->errors
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $model->errors
            ];
        }
    }

    public function actionUpdate($id)
    {
        $model = Product::findOne($id);
        
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Product not found');
        }
        
        // Get sanitized data from request (already processed by SecurityFilter)
        $data = Yii::$app->request->getBodyParams();
        
        if ($data && $model->load($data, '')) {
            if ($model->save()) {
                return [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => $model
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update product',
                    'errors' => $model->errors
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $model->errors
            ];
        }
    }

    public function actionDelete($id)
    {
        $model = Product::findOne($id);
        
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Product not found');
        }
        
        // Store the image path before deleting the model
        $imagePath = $model->image_path;
        
        if ($model->delete()) {
            // Delete the associated image file if it exists
            if ($imagePath) {
                $this->deleteImageFile($imagePath);
            }
            
            return [
                'success' => true,
                'message' => 'Product deleted successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to delete product',
                'errors' => $model->errors
            ];
        }
    }
    
    /**
     * Delete image file from public directory
     */
    private function deleteImageFile($imagePath)
    {
        // Remove leading slash if present
        $imagePath = ltrim($imagePath, '/');
        
        // Construct full file path
        $fullPath = dirname(Yii::getAlias('@app')) . '/public/' . $imagePath;
        
        // Check if file exists and delete it
        if (file_exists($fullPath) && is_file($fullPath)) {
            try {
                unlink($fullPath);
                Yii::info("Deleted image file: {$fullPath}", __METHOD__);
            } catch (\Exception $e) {
                Yii::error("Failed to delete image file {$fullPath}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
