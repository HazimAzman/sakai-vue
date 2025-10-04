<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Download;

class SeedDownloadsCommand extends Controller
{
    public function actionIndex()
    {
        $sampleDownloads = [
            ['brand_name' => 'Accutec', 'download_url' => '#'],
            ['brand_name' => 'Ametek', 'download_url' => '#'],
            ['brand_name' => 'Arcs', 'download_url' => '#'],
            ['brand_name' => 'Center', 'download_url' => '#'],
            ['brand_name' => 'Constance Lab', 'download_url' => '#'],
            ['brand_name' => 'Daihan', 'download_url' => '#'],
            ['brand_name' => 'Defelsko', 'download_url' => '#'],
            ['brand_name' => 'Eisen', 'download_url' => '#'],
            ['brand_name' => 'Eyela', 'download_url' => '#'],
            ['brand_name' => 'Hettich', 'download_url' => '#'],
            ['brand_name' => 'IKA', 'download_url' => '#'],
            ['brand_name' => 'Interscience', 'download_url' => '#'],
            ['brand_name' => 'Julabo', 'download_url' => '#'],
            ['brand_name' => 'Kibron', 'download_url' => '#'],
            ['brand_name' => 'MembraPure', 'download_url' => '#'],
            ['brand_name' => 'Mettler Toledo', 'download_url' => '#'],
            ['brand_name' => 'Milwaukee', 'download_url' => '#'],
            ['brand_name' => 'Minebea Intec', 'download_url' => '#'],
            ['brand_name' => 'Mitutoyo', 'download_url' => '#'],
            ['brand_name' => 'Nabertherm', 'download_url' => '#'],
            ['brand_name' => 'N-Biotech', 'download_url' => '#'],
            ['brand_name' => 'Optika', 'download_url' => '#'],
            ['brand_name' => 'Perkin Elmer', 'download_url' => '#'],
            ['brand_name' => 'RaxVision', 'download_url' => '#'],
            ['brand_name' => 'RCPAQAP', 'download_url' => '#'],
            ['brand_name' => 'Renishaw', 'download_url' => '#'],
            ['brand_name' => 'Rocker', 'download_url' => '#'],
            ['brand_name' => 'Sartorius', 'download_url' => '#'],
            ['brand_name' => 'Scilab', 'download_url' => '#'],
            ['brand_name' => 'Shimadzu', 'download_url' => '#'],
            ['brand_name' => 'Shimpo', 'download_url' => '#'],
            ['brand_name' => 'StereCo', 'download_url' => '#'],
            ['brand_name' => 'Thermo', 'download_url' => '#'],
            ['brand_name' => 'TIME', 'download_url' => '#'],
            ['brand_name' => 'Toptech', 'download_url' => '#'],
            ['brand_name' => 'Velp', 'download_url' => '#'],
            ['brand_name' => 'Wason Marlow', 'download_url' => '#'],
            ['brand_name' => 'Welch', 'download_url' => '#'],
            ['brand_name' => 'Yamamoto', 'download_url' => '#']
        ];

        foreach ($sampleDownloads as $downloadData) {
            $download = new Download();
            $download->brand_name = $downloadData['brand_name'];
            $download->download_url = $downloadData['download_url'];
            
            if ($download->save()) {
                echo "Created download: {$download->brand_name}\n";
            } else {
                echo "Failed to create download: {$download->brand_name}\n";
                print_r($download->errors);
            }
        }
        
        echo "Download seeding completed.\n";
    }
}
