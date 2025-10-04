<?php

namespace app\commands;

use yii\console\Controller;
use app\models\About;

class SeedAboutCommand extends Controller
{
    public function actionIndex()
    {
        $aboutData = [
            'title' => 'BRINGING YOUR LAB SOLUTION & NEEDS',
            'content' => 'AZTEC SINAR SDN BHD was incorporated in 2004 and has grown to become a leading supplier of scientific instrumentation to research, lab services, education, clinical, and pathology labs throughout Malaysia. We offer sales and rentals of test and measurement, monitoring, and general scientific equipment.

AZTEC SINAR distributes and provides services across Malaysia, including Sabah and Sarawak. Our clientele includes universities, education colleges, training centers, research centers, service laboratories, and pathology labs under various Malaysian government ministries, as well as selected private industries like factories and test labs.

AZTEC SINAR\'s capabilities include equipment installation (analytical instruments, test measurement, environmental monitoring devices), product training, and professional services in collaboration with local and international partners. Our project experience spans government and GLC sectors, including oil and gas, palm mills, bio-gas plants, water treatment, clinical and pathology labs, life science and molecular research, manufacturing, and service lab clients throughout Malaysia, Sabah, and Sarawak.',
            'ceo_name' => 'Azman Yunus',
            'ceo_title' => 'Chief Executive Officer',
            'ceo_image' => '/images/about/azman-yunus.png'
        ];

        $about = new About();
        $about->title = $aboutData['title'];
        $about->content = $aboutData['content'];
        $about->ceo_name = $aboutData['ceo_name'];
        $about->ceo_title = $aboutData['ceo_title'];
        $about->ceo_image = $aboutData['ceo_image'];
        
        if ($about->save()) {
            echo "Created about entry: {$about->title}\n";
        } else {
            echo "Failed to create about entry: {$about->title}\n";
            print_r($about->errors);
        }
        
        echo "About seeding completed.\n";
    }
}
