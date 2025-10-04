<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Product;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Seeding sample data...\n");
        
        // Clear existing data
        Product::deleteAll();
        
        // Sample products
        $products = [
            [
                'name' => 'Vue.js Complete Guide',
                'description' => 'A comprehensive guide to Vue.js development with modern practices and best practices.',
                'price' => 49.99,
            ],
            [
                'name' => 'Yii2 Framework Essentials',
                'description' => 'Learn the fundamentals of Yii2 framework for building robust web applications.',
                'price' => 39.99,
            ],
            [
                'name' => 'Full Stack Development Course',
                'description' => 'Complete course covering frontend (Vue.js) and backend (Yii2) development.',
                'price' => 89.99,
            ],
            [
                'name' => 'API Development Masterclass',
                'description' => 'Master RESTful API development with Yii2 and modern frontend frameworks.',
                'price' => 59.99,
            ],
            [
                'name' => 'Database Design Fundamentals',
                'description' => 'Learn database design principles and implementation with MySQL.',
                'price' => 29.99,
            ],
        ];
        
        foreach ($products as $productData) {
            $product = new Product();
            $product->name = $productData['name'];
            $product->description = $productData['description'];
            $product->price = $productData['price'];
            
            if ($product->save()) {
                $this->stdout("Created product: {$product->name}\n");
            } else {
                $this->stderr("Failed to create product: {$product->name}\n");
                $this->stderr(print_r($product->errors, true) . "\n");
            }
        }
        
        $this->stdout("Seeding completed!\n");
    }
}
