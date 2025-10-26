<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use app\models\About;
use app\filters\SecurityFilter;
use app\services\DataSanitizer;

class AboutController extends ActiveController
{
    public $modelClass = 'app\models\About';

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

        // Require auth for write endpoints only
        $behaviors['securityFilter'] = [
            'class' => SecurityFilter::class,
            'only' => ['create', 'update', 'delete'],
            'requireAuth' => true,
            'requirePermissionLevel' => 1,
            'rateLimit' => true,
            'rateLimitEndpoint' => 'about',
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
        // Enforce auth for write methods
        $this->enforceWriteAuth();
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
        // Get the raw data first
        $query = About::find();
        $models = $query->all();
        
        // Sanitize the data
        $sanitizedModels = DataSanitizer::sanitizeArray($models, 'sanitizeAbout');
        
        // Create a custom data provider with sanitized data
        return new \yii\data\ArrayDataProvider([
            'allModels' => $sanitizedModels,
            'pagination' => false,
        ]);
    }

    public function actionCreate()
    {
        $model = new About();
        
        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        
        if ($data) {
            // Manually set attributes
            if (isset($data['title'])) {
                $model->title = $data['title'];
            }
            if (isset($data['content'])) {
                $model->content = $data['content'];
            }
            if (isset($data['ceo_name'])) {
                $model->ceo_name = $data['ceo_name'];
            }
            if (isset($data['ceo_title'])) {
                $model->ceo_title = $data['ceo_title'];
            }
            if (isset($data['ceo_image'])) {
                $model->ceo_image = $data['ceo_image'];
            }
            
            if ($model->save()) {
                return $model;
            } else {
                return [
                    'success' => false,
                    'errors' => $model->errors,
                    'message' => 'Failed to create about entry'
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
        $model = About::findOne($id);
        
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('About entry not found');
        }
        
        // Get JSON data from request body
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        
        if ($data) {
            // Manually set attributes
            if (isset($data['title'])) {
                $model->title = $data['title'];
            }
            if (isset($data['content'])) {
                $model->content = $data['content'];
            }
            if (isset($data['ceo_name'])) {
                $model->ceo_name = $data['ceo_name'];
            }
            if (isset($data['ceo_title'])) {
                $model->ceo_title = $data['ceo_title'];
            }
            if (isset($data['ceo_image'])) {
                $model->ceo_image = $data['ceo_image'];
            }
            
            if ($model->save()) {
                return $model;
            } else {
                return [
                    'success' => false,
                    'errors' => $model->errors,
                    'message' => 'Failed to update about entry'
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
        $model = About::findOne($id);
        
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('About entry not found');
        }
        
        if ($model->delete()) {
            return ['message' => 'About entry deleted successfully'];
        } else {
            return ['error' => 'Failed to delete about entry'];
        }
    }
}
