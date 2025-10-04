<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Product;

class SeedProductsCommand extends Controller
{
    public function actionIndex()
    {
        $sampleProducts = [
            // Row 1
            [
                'name' => 'Witeg',
                'description' => 'Laboratory Instrument',
                'image_path' => '/images/product/witeg-150.png',
                'category' => 'Laboratory Instrument'
            ],
            [
                'name' => 'Minebea Intec',
                'description' => 'Advance Weighing System',
                'image_path' => '/images/product/minebea-150.png',
                'category' => 'Advance Weighing System'
            ],
            [
                'name' => 'IKA',
                'description' => 'Liquid Handling Instrument',
                'image_path' => '/images/product/ika-150.png',
                'category' => 'Liquid Handling Instrument'
            ],
            [
                'name' => 'OPTIKA ITALY',
                'description' => 'Microscope & Inspection System',
                'image_path' => '/images/product/optika-150.png',
                'category' => 'Microscope & Inspection System'
            ],
            // Row 2
            [
                'name' => 'Fischer',
                'description' => 'Material Coating Thickness & Analysis Instrument',
                'image_path' => '/images/product/fishersciencetific-150.png',
                'category' => 'Material Coating Thickness & Analysis Instrument'
            ],
            [
                'name' => 'TOP TECH',
                'description' => 'Sample Preparation Equipment',
                'image_path' => '/images/product/toptech-150.png',
                'category' => 'Sample Preparation Equipment'
            ],
            [
                'name' => 'WELCH',
                'description' => 'Vacuum System',
                'image_path' => '/images/product/welch-150.png',
                'category' => 'Vacuum System'
            ],
            [
                'name' => 'Vision ENGINEERING',
                'description' => 'Microscope System',
                'image_path' => '/images/product/vision-150.png',
                'category' => 'Microscope System'
            ],
            // Row 3
            [
                'name' => 'Sartorius',
                'description' => 'pH Meter & Ultrapure Water System',
                'image_path' => '/images/product/sartorius-150.png',
                'category' => 'pH Meter & Ultrapure Water System'
            ],
            [
                'name' => 'Fisher Scientific',
                'description' => 'Lab Essential',
                'image_path' => '/images/product/fishersciencetific-150.png',
                'category' => 'Lab Essential'
            ],
            [
                'name' => 'EYELA',
                'description' => 'Rotary Evaporator',
                'image_path' => '/images/product/eyela-150.png',
                'category' => 'Rotary Evaporator'
            ],
            [
                'name' => 'AccuTEC',
                'description' => 'Weighing System',
                'image_path' => '/images/product/accutec-150.png',
                'category' => 'Weighing System'
            ],
            // Row 4
            [
                'name' => 'GOTECH',
                'description' => 'Material Testing Equipment',
                'image_path' => '/images/product/gotech-150.png',
                'category' => 'Material Testing Equipment'
            ],
            [
                'name' => 'RENISHAW',
                'description' => 'Probing System & Styli',
                'image_path' => '/images/product/renishaw-150.png',
                'category' => 'Probing System & Styli'
            ],
            [
                'name' => 'FEDEGARI LAB DIVISION',
                'description' => 'Steam Sterilizer, Washer & Bio-decontamination System',
                'image_path' => '/images/product/fedegari-150.png',
                'category' => 'Steam Sterilizer, Washer & Bio-decontamination System'
            ],
            [
                'name' => 'Julabo',
                'description' => 'Circular, Water Bath & Cooler',
                'image_path' => '/images/product/julabo-150.png',
                'category' => 'Circular, Water Bath & Cooler'
            ],
            // Row 5
            [
                'name' => 'Anton Paar',
                'description' => 'Measurement & Analysis Instrument',
                'image_path' => '/images/product/antonpaar-150.png',
                'category' => 'Measurement & Analysis Instrument'
            ],
            [
                'name' => 'Interscience',
                'description' => 'Sample Preparation & Analysis System',
                'image_path' => '/images/product/interscience-150.png',
                'category' => 'Sample Preparation & Analysis System'
            ],
            [
                'name' => 'AROS',
                'description' => 'Metrology Equipment',
                'image_path' => '/images/product/arcs-150.png',
                'category' => 'Metrology Equipment'
            ],
            [
                'name' => 'Constance',
                'description' => 'Oven, Incubator & Furnace',
                'image_path' => '/images/product/constance-150.png',
                'category' => 'Oven, Incubator & Furnace'
            ],
            // Row 6
            [
                'name' => 'Milwaukee',
                'description' => 'Liquid Analysis Meter',
                'image_path' => '/images/product/milwaukee-150.png',
                'category' => 'Liquid Analysis Meter'
            ],
            [
                'name' => 'Kibron',
                'description' => 'Surface Tension Instrument',
                'image_path' => '/images/product/kibron-150.png',
                'category' => 'Surface Tension Instrument'
            ],
            [
                'name' => 'BaxVision',
                'description' => 'Microscope & Inspection System',
                'image_path' => '/images/product/raxvision-150.png',
                'category' => 'Microscope & Inspection System'
            ],
            [
                'name' => 'VELP SCIENTIFICA',
                'description' => 'Food & Feed, Environmental Analyser & Stirring Instrument',
                'image_path' => '/images/product/velp-150.png',
                'category' => 'Food & Feed, Environmental Analyser & Stirring Instrument'
            ],
            // Row 7
            [
                'name' => 'ThermoFisher SCIENTIFIC',
                'description' => 'Freezer & Refrigerator',
                'image_path' => '/images/product/thermofisher-150.png',
                'category' => 'Freezer & Refrigerator'
            ],
            [
                'name' => 'membraPure',
                'description' => 'Water Purification System & TOC Analyzer',
                'image_path' => '/images/product/membrapure-150.png',
                'category' => 'Water Purification System & TOC Analyzer'
            ],
            [
                'name' => 'SHIMPO',
                'description' => 'Force & Speed Measuring Instrument',
                'image_path' => '/images/product/shimpo-150.png',
                'category' => 'Force & Speed Measuring Instrument'
            ],
            [
                'name' => 'Hettich LAB TECHNOLOGY',
                'description' => 'Centrifuge, Pipette & Incubator',
                'image_path' => '/images/product/hettich-150.png',
                'category' => 'Centrifuge, Pipette & Incubator'
            ],
            // Row 8
            [
                'name' => 'Nabertherm',
                'description' => 'Furnace',
                'image_path' => '/images/product/nabertherm-150.png',
                'category' => 'Furnace'
            ],
            [
                'name' => 'Mitutoyo',
                'description' => 'Measuring & Testing Instrument',
                'image_path' => '/images/product/mitutoyo-150.png',
                'category' => 'Measuring & Testing Instrument'
            ],
            [
                'name' => 'SHIMADZU',
                'description' => 'Electronic Balance',
                'image_path' => '/images/product/shimadzu-150.png',
                'category' => 'Electronic Balance'
            ],
            [
                'name' => 'CENTER',
                'description' => 'Environment Measuring Meter',
                'image_path' => '/images/product/center-150.png',
                'category' => 'Environment Measuring Meter'
            ],
            // Row 9
            [
                'name' => 'JASCO',
                'description' => 'Chromatography & Spectrophotometer',
                'image_path' => '/images/product/jasco-150.png',
                'category' => 'Chromatography & Spectrophotometer'
            ],
            [
                'name' => 'YSI',
                'description' => 'Environmental Data Collection',
                'image_path' => '/images/product/ysi-150.png',
                'category' => 'Environmental Data Collection'
            ],
            [
                'name' => 'LABCONCO',
                'description' => 'Sample Preparation',
                'image_path' => '/images/product/labconco-150.png',
                'category' => 'Sample Preparation'
            ],
            [
                'name' => 'RCPAQAP',
                'description' => 'External Quality Program for Pathology Test',
                'image_path' => '/images/product/rcpaqap-150.png',
                'category' => 'External Quality Program for Pathology Test'
            ],
            // Row 10
            [
                'name' => 'HUMAN',
                'description' => 'Blood Group and Analysis',
                'image_path' => '/images/product/human-150.png',
                'category' => 'Blood Group and Analysis'
            ],
            [
                'name' => 'TSI',
                'description' => 'Environmental Gas Analysis',
                'image_path' => '/images/product/tsi-150.png',
                'category' => 'Environmental Gas Analysis'
            ],
            [
                'name' => 'Trinity Biotech',
                'description' => 'Hblac Analysis (Blood Analysis)',
                'image_path' => '/images/product/trinitybiotech-150.png',
                'category' => 'Hblac Analysis (Blood Analysis)'
            ],
            [
                'name' => 'DURAN',
                'description' => 'Laboratory Glassware',
                'image_path' => '/images/product/duran-150.png',
                'category' => 'Laboratory Glassware'
            ],
            // Row 11
            [
                'name' => 'HACH',
                'description' => 'Reagent and Lab Analysis',
                'image_path' => '/images/product/hach-150.png',
                'category' => 'Reagent and Lab Analysis'
            ],
            [
                'name' => 'HIRAYAMA',
                'description' => 'Sample Sterilizer',
                'image_path' => '/images/product/hirayama-150.png',
                'category' => 'Sample Sterilizer'
            ],
            [
                'name' => 'EXTECH',
                'description' => 'Test Meter',
                'image_path' => '/images/product/extech-150.png',
                'category' => 'Test Meter'
            ],
            [
                'name' => 'SPL',
                'description' => 'Consumables',
                'image_path' => '/images/product/spllifescience-150.png',
                'category' => 'Consumables'
            ],
            // Row 12
            [
                'name' => 'BUCHI',
                'description' => 'Rotary Evaporator',
                'image_path' => '/images/product/buchi-150.png',
                'category' => 'Rotary Evaporator'
            ],
            [
                'name' => 'gastrak',
                'description' => 'Environmental Gas Analysis',
                'image_path' => '/images/product/gastrak-150.png',
                'category' => 'Environmental Gas Analysis'
            ],
            [
                'name' => 'MERCK',
                'description' => 'Laboratory Chemicals & Solvent',
                'image_path' => '/images/product/merck-150.png',
                'category' => 'Laboratory Chemicals & Solvent'
            ]
        ];

        foreach ($sampleProducts as $productData) {
            $product = new Product();
            $product->name = $productData['name'];
            $product->description = $productData['description'];
            $product->image_path = $productData['image_path'];
            $product->category = $productData['category'];
            
            if ($product->save()) {
                echo "Created product: {$product->name}\n";
            } else {
                echo "Failed to create product: {$product->name}\n";
                print_r($product->errors);
            }
        }
        
        echo "Product seeding completed.\n";
    }
}
