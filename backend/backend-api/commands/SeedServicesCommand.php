<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Service;

class SeedServicesCommand extends Controller
{
    public function actionIndex()
    {
        $sampleServices = [
            [
                'title' => 'Lab & Scientific',
                'description' => 'Equipments Supply',
                'image_path' => '/images/service/lab-scientific-equipments-supply.png'
            ],
            [
                'title' => 'Lab Safety &',
                'description' => 'Apparatus Supply',
                'image_path' => '/images/service/lab-safety-apparatus-supply.png'
            ],
            [
                'title' => 'Analytical',
                'description' => 'Instruments Supply',
                'image_path' => '/images/service/analytical-instruments-supply.png'
            ],
            [
                'title' => 'Lab Chemical',
                'description' => 'Consumables Supply',
                'image_path' => '/images/service/lab-chemical-consumables-supply.png'
            ],
            [
                'title' => 'Life Science',
                'description' => 'Molecular Supply',
                'image_path' => '/images/service/life-science-molecular-supply.png'
            ],
            [
                'title' => 'Test &',
                'description' => 'Measurement Supply',
                'image_path' => '/images/service/test-measurement-supply.png'
            ],
            [
                'title' => 'Equipment',
                'description' => 'Rental Services',
                'image_path' => '/images/service/equipment-rental-services.png'
            ],
            [
                'title' => 'Service Repair',
                'description' => '& Maintenance Service',
                'image_path' => '/images/service/service-repair-maintenance-service.png'
            ],
            [
                'title' => 'Pathology Lab',
                'description' => 'Supply',
                'image_path' => '/images/service/pathology-sab-supply.png'
            ],
            [
                'title' => 'Environmental',
                'description' => 'Monitoring Supply',
                'image_path' => '/images/service/enviromental-monitoring-supply.png'
            ],
            [
                'title' => 'Education',
                'description' => 'Technology Supply',
                'image_path' => '/images/service/education-technology-supply.png'
            ],
            [
                'title' => 'Software & Data',
                'description' => 'Collection Supply',
                'image_path' => '/images/service/sofware-data-collection-supply.png'
            ]
        ];

        foreach ($sampleServices as $serviceData) {
            $service = new Service();
            $service->title = $serviceData['title'];
            $service->description = $serviceData['description'];
            $service->image_path = $serviceData['image_path'];
            
            if ($service->save()) {
                echo "Created service: {$service->title}\n";
            } else {
                echo "Failed to create service: {$service->title}\n";
                print_r($service->errors);
            }
        }
        
        echo "Service seeding completed.\n";
    }
}
