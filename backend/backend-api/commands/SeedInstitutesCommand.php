<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Institute;

class SeedInstitutesCommand extends Controller
{
    public function actionIndex()
    {
        echo "Seeding institutes data...\n";

        $institutes = [
            [
                'name' => 'Jabatan Tenaga Manusia',
                'abbreviation' => 'jTm',
                'image_path' => '/images/institut/jtm.png',
                'description' => 'Department of Human Resources'
            ],
            [
                'name' => 'Institut Penyelidikan Perubatan Malaysia',
                'abbreviation' => 'IMR',
                'image_path' => '/images/institut/imr.png',
                'description' => 'Institute for Medical Research Malaysia'
            ],
            [
                'name' => 'Institut Penyelidikan Dan Kemajuan Pertanian Malaysia',
                'abbreviation' => 'MARDI',
                'image_path' => '/images/institut/mardi.png',
                'description' => 'Malaysian Agricultural Research and Development Institute'
            ],
            [
                'name' => 'Lembaga Getah Malaysia',
                'abbreviation' => 'LGM',
                'image_path' => '/images/institut/lgm.png',
                'description' => 'Malaysian Rubber Board'
            ],
            [
                'name' => 'Institut Penyelidikan Perhutanan',
                'abbreviation' => 'FRIM',
                'image_path' => '/images/institut/frim.png',
                'description' => 'Forest Research Institute Malaysia'
            ],
            [
                'name' => 'Halal Malaysia',
                'abbreviation' => 'HALAL',
                'image_path' => '/images/institut/halal.png',
                'description' => 'Halal Malaysia'
            ],
            [
                'name' => 'Lembaga Kemajuan Tanah Persekutuan',
                'abbreviation' => 'FELDA',
                'image_path' => '/images/institut/felda.png',
                'description' => 'Federal Land Development Authority'
            ],
            [
                'name' => 'Petroliam Nasional Berhad',
                'abbreviation' => 'PETRONAS',
                'image_path' => '/images/institut/petronas.png',
                'description' => 'Petroliam Nasional Berhad'
            ],
            [
                'name' => 'Jabatan Kimia Malaysia',
                'abbreviation' => 'KIMIA',
                'image_path' => '/images/institut/kimia.png',
                'description' => 'Chemistry Department Malaysia'
            ],
            [
                'name' => 'Jabatan Perikanan Malaysia',
                'abbreviation' => 'JPM',
                'image_path' => '/images/institut/jpm.png',
                'description' => 'Department of Fisheries Malaysia'
            ],
            [
                'name' => 'Jabatan Pertanian',
                'abbreviation' => 'JP',
                'image_path' => '/images/institut/jp.png',
                'description' => 'Department of Agriculture'
            ],
            [
                'name' => 'Lembaga Minyak Sawit Malaysia',
                'abbreviation' => 'MPOB',
                'image_path' => '/images/institut/mpob.png',
                'description' => 'Malaysian Palm Oil Board'
            ]
        ];

        foreach ($institutes as $instituteData) {
            $institute = new Institute();
            $institute->name = $instituteData['name'];
            $institute->abbreviation = $instituteData['abbreviation'];
            $institute->image_path = $instituteData['image_path'];
            $institute->description = $instituteData['description'];
            
            if ($institute->save()) {
                echo "Created institute: {$institute->name}\n";
            } else {
                echo "Failed to create institute: {$institute->name}\n";
                print_r($institute->errors);
            }
        }

        echo "Institutes seeding completed!\n";
    }
}
