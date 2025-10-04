<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Client;

class SeedClientsCommand extends Controller
{
    public function actionIndex()
    {
        $sampleClients = [
            ['name' => 'Universiti Malaysia Kelantan', 'short_name' => 'UMK', 'logo_path' => '/images/universiti/umk.png'],
            ['name' => 'Universiti Malaya', 'short_name' => 'UM', 'logo_path' => '/images/universiti/um.png'],
            ['name' => 'Universiti Putra Malaysia', 'short_name' => 'UPM', 'logo_path' => '/images/universiti/upm.png'],
            ['name' => 'Universiti Malaysia Terengganu', 'short_name' => 'UMT', 'logo_path' => '/images/universiti/umt.png'],
            ['name' => 'Universiti Kebangsaan Malaysia', 'short_name' => 'UKM', 'logo_path' => '/images/universiti/ukm.png'],
            ['name' => 'Universiti Teknologi Malaysia', 'short_name' => 'UTM', 'logo_path' => '/images/universiti/utm.png'],
            ['name' => 'Universiti Malaysia Sarawak', 'short_name' => 'UNIMAS', 'logo_path' => '/images/universiti/unimas.png'],
            ['name' => 'Universiti Malaysia Pahang', 'short_name' => 'UMP', 'logo_path' => '/images/universiti/ump.png'],
            ['name' => 'Universiti Teknologi Mara', 'short_name' => 'UiTM', 'logo_path' => '/images/universiti/uitm.png'],
            ['name' => 'Universiti Malaysia Sabah', 'short_name' => 'UMS', 'logo_path' => '/images/universiti/ums.png'],
            ['name' => 'Universiti Pendidikan Sultan Idris', 'short_name' => 'UPSI', 'logo_path' => '/images/universiti/upsi.png'],
            ['name' => 'Universiti Teknikal Malaysia Melaka', 'short_name' => 'UTeM', 'logo_path' => '/images/universiti/utem.png'],
            ['name' => 'Universiti Pertahanan Nasional Malaysia', 'short_name' => 'UPNM', 'logo_path' => '/images/universiti/upnm.png'],
            ['name' => 'Universiti Sains Islam Malaysia', 'short_name' => 'USIM', 'logo_path' => '/images/universiti/usim.png'],
            ['name' => 'Universiti Islam Antarabangsa Malaysia', 'short_name' => 'UIA', 'logo_path' => '/images/universiti/uia.png'],
            ['name' => 'Universiti Tun Hussein Onn Malaysia', 'short_name' => 'UTHM', 'logo_path' => '/images/universiti/uthm.png'],
            ['name' => 'Universiti Sultan Zainal Abidin', 'short_name' => 'UniSZA', 'logo_path' => '/images/universiti/unisza.png'],
            ['name' => 'Universiti Malaysia Perlis', 'short_name' => 'UniMAP', 'logo_path' => '/images/universiti/unimap.png'],
            ['name' => 'Universiti Sains Malaysia', 'short_name' => 'USM', 'logo_path' => '/images/universiti/usm.png'],
            ['name' => 'Universiti Utara Malaysia', 'short_name' => 'UUM', 'logo_path' => '/images/universiti/uum.png']
        ];

        foreach ($sampleClients as $clientData) {
            $client = new Client();
            $client->name = $clientData['name'];
            $client->short_name = $clientData['short_name'];
            $client->logo_path = $clientData['logo_path'];
            
            if ($client->save()) {
                echo "Created client: {$client->name}\n";
            } else {
                echo "Failed to create client: {$client->name}\n";
                print_r($client->errors);
            }
        }
        
        echo "Client seeding completed.\n";
    }
}
