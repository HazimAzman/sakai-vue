<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Activity;

class SeedActivitiesCommand extends Controller
{
    public function actionIndex()
    {
        echo "Seeding activities data...\n";

        $activitiesData = [
            [
                'title' => 'Laboratory Equipment Installation',
                'description' => 'Professional installation and setup of laboratory equipment including analytical instruments, test measurement devices, and environmental monitoring systems. Our certified technicians ensure proper calibration and functionality.',
                'image_path' => '/images/activities/equipment-installation.jpg',
            ],
            [
                'title' => 'Product Training & Certification',
                'description' => 'Comprehensive training programs for laboratory staff on equipment operation, maintenance, and safety protocols. We provide certification courses to ensure optimal equipment utilization and safety compliance.',
                'image_path' => '/images/activities/training-certification.jpg',
            ],
            [
                'title' => 'Equipment Maintenance & Service',
                'description' => 'Regular maintenance and repair services for all laboratory equipment. Our service team provides preventive maintenance, troubleshooting, and emergency repair services to ensure continuous operation.',
                'image_path' => '/images/activities/maintenance-service.jpg',
            ],
            [
                'title' => 'Research Collaboration',
                'description' => 'Partnership with universities and research institutions to support scientific research projects. We provide technical expertise, equipment support, and collaborative research opportunities.',
                'image_path' => '/images/activities/research-collaboration.jpg',
            ],
            [
                'title' => 'Quality Assurance Testing',
                'description' => 'Comprehensive quality assurance testing services for laboratory equipment and processes. We ensure compliance with international standards and provide detailed testing reports.',
                'image_path' => '/images/activities/quality-assurance.jpg',
            ],
            [
                'title' => 'Technical Consultation',
                'description' => 'Expert technical consultation services for laboratory setup, equipment selection, and process optimization. Our experienced team provides tailored solutions for your specific requirements.',
                'image_path' => '/images/activities/technical-consultation.jpg',
            ],
        ];

        foreach ($activitiesData as $data) {
            $activity = new Activity();
            $activity->title = $data['title'];
            $activity->description = $data['description'];
            $activity->image_path = $data['image_path'];
            if ($activity->save()) {
                echo "Created activity: " . $activity->title . "\n";
            } else {
                echo "Error creating activity: " . print_r($activity->errors, true) . "\n";
            }
        }

        echo "Activities seeding completed!\n";
    }
}
