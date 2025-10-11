<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use app\models\Activity;
use app\filters\SecurityFilter;

class ActivityController extends ActiveController
{
    public $modelClass = 'app\models\Activity';

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

        // Require auth for all activity endpoints
        $behaviors['securityFilter'] = [
            'class' => SecurityFilter::class,
            'only' => ['create', 'update', 'delete'],
            'requireAuth' => true,
            'requirePermissionLevel' => 1,
            'rateLimit' => true,
            'rateLimitEndpoint' => 'activities',
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
            'query' => Activity::find(),
            'pagination' => false, // Disable pagination to return all activities
        ]);
    }

    public function actionCreate()
    {
        $model = new Activity();

        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        if ($data) {
            // Manually set attributes
            if (isset($data['title'])) {
                $model->title = $data['title'];
            }
            if (isset($data['description'])) {
                $model->description = $data['description'];
            }
            if (isset($data['image_path'])) {
                $model->image_path = $data['image_path'];
            }

            if ($model->save()) {
                return $model;
            } else {
                return [
                    'success' => false,
                    'errors' => $model->errors,
                    'message' => 'Failed to create activity'
                ];
            }
        } else {
            return [
                'success' => false,
                'errors' => ['Invalid JSON data'],
                'message' => 'Invalid JSON data provided'
            ];
        }
    }

    public function actionUpdate($id)
    {
        $model = Activity::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Activity not found');
        }

        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        if ($data) {
            // Manually set attributes
            if (isset($data['title'])) {
                $model->title = $data['title'];
            }
            if (isset($data['description'])) {
                $model->description = $data['description'];
            }
            if (isset($data['image_path'])) {
                $model->image_path = $data['image_path'];
            }

            if ($model->save()) {
                return $model;
            } else {
                return [
                    'success' => false,
                    'errors' => $model->errors,
                    'message' => 'Failed to update activity'
                ];
            }
        } else {
            return [
                'success' => false,
                'errors' => ['Invalid JSON data'],
                'message' => 'Invalid JSON data provided'
            ];
        }
    }

    public function actionDelete($id)
    {
        $model = Activity::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Activity not found');
        }

        // Store the image path before deleting the model
        $imagePath = $model->image_path;

        if ($model->delete()) {
            // Delete the associated image file if it exists
            if ($imagePath) {
                $this->deleteImageFile($imagePath);
            }
            
            return ['message' => 'Activity deleted successfully'];
        } else {
            return ['error' => 'Failed to delete activity'];
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
