<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Contact;

class SeedContactsCommand extends Controller
{
    public function actionIndex()
    {
        $sampleContacts = [
            [
                'office_name' => 'Northern Office',
                'address' => 'Plot 85B, Lintang Bayan Lepas 9, Bayan Lepas Industrial Park Phase 4, 11900 Bayan Lepas, Penang',
                'phone' => '04-6432 080'
            ],
            [
                'office_name' => 'Central Office',
                'address' => 'Lot 12A, Jalan Gitar 33/3, Elite Industrial Park, 40350 Shah Alam, Selangor',
                'phone' => '03-5121 2673'
            ],
            [
                'office_name' => 'Southern Office',
                'address' => 'No 19, Jalan Cantik 3, Taman Pelangi Indah, 81800 Ulu Tiram, Johor',
                'phone' => '07-8619511' . "\n" . '07-8612790'
            ],
            [
                'office_name' => 'Sarawak Office',
                'address' => '9th Floor, Bangunan Binamas, Jalan Padungan, 93100 Kuching, Sarawak',
                'phone' => '016-8079 616'
            ],
            [
                'office_name' => 'Sabah Office',
                'address' => '8-1, 8th Floor, Suria Sabah Shopping Mall, No.1, Jalan Tun Fuad Stephen, 88000 Kota Kinabalu, Sabah',
                'phone' => '016-8398 627' . "\n" . '088-366 500'
            ]
        ];

        foreach ($sampleContacts as $contactData) {
            $contact = new Contact();
            $contact->office_name = $contactData['office_name'];
            $contact->address = $contactData['address'];
            $contact->phone = $contactData['phone'];
            
            if ($contact->save()) {
                echo "Created contact: {$contact->office_name}\n";
            } else {
                echo "Failed to create contact: {$contact->office_name}\n";
                print_r($contact->errors);
            }
        }
        
        echo "Contact seeding completed.\n";
    }
}
