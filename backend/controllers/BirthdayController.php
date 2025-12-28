<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Birthday;

/**
 * BirthdayController handles the birthday celebration page
 * 
 * @package app\controllers
 */
class BirthdayController extends Controller
{
    /**
     * Use custom layout for birthday page
     */
    public $layout = 'birthday';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // You can add access control, rate limiting, etc. here
        ];
    }

    /**
     * Displays the birthday celebration page
     * 
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        try {
            // Get birthday data from model
            $birthday = Birthday::getDefaultBirthday();

            // Validate model (though it's using default data)
            if (!$birthday->validate()) {
                Yii::warning(
                    'Birthday model validation failed: ' . json_encode($birthday->errors),
                    __METHOD__
                );
            }

            return $this->render('index', [
                'model' => $birthday,
            ]);
        } catch (\Exception $e) {
            Yii::error(
                'Error rendering birthday page: ' . $e->getMessage(),
                __METHOD__
            );
            throw new \yii\web\ServerErrorHttpException(
                'An error occurred while loading the birthday page. Please try again later.'
            );
        }
    }
}

