<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Birthday Model
 * 
 * Represents birthday celebration data including schedule and birthday girl information
 */
class Birthday extends Model
{
    /**
     * @var string Birthday girl's name
     */
    public $name;

    /**
     * @var string Birthday girl's full name
     */
    public $fullName;

    /**
     * @var string Birthday message
     */
    public $message;

    /**
     * @var array Celebration schedule items
     */
    public $schedule = [];

    /**
     * @var string Image path
     */
    public $imagePath;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fullName'], 'required'],
            [['name', 'fullName', 'message', 'imagePath'], 'string'],
            [['schedule'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'fullName' => 'Full Name',
            'message' => 'Birthday Message',
            'schedule' => 'Schedule',
            'imagePath' => 'Image Path',
        ];
    }

    /**
     * Get default birthday data for Mimi Umairah Liyana
     * 
     * @return Birthday
     */
    public static function getDefaultBirthday()
    {
        $model = new self();
        $model->name = 'Mimi Umairah Liyana';
        $model->fullName = 'Mimi Umairah Liyana';
        $model->message = 'Happy Birthday! Wishing you a day filled with joy, laughter, and wonderful memories!';
        
        $model->schedule = [
            [
                'time' => '5:50 PM',
                'activity' => 'Pickup birthday girl from Bakti Hostel',
                'icon' => '🚗',
                'highlight' => false,
                'clue' => null,
            ],
            [
                'time' => '6:20 PM',
                'activity' => 'Arrive at Venue',
                'icon' => '📍',
                'highlight' => false,
                'clue' => 'Bayan Baru',
            ],
            [
                'time' => '6:50 PM',
                'activity' => 'Birthday Gift Unboxing',
                'icon' => '🎁',
                'highlight' => true,
                'clue' => null,
            ],
            [
                'time' => '7:15 PM',
                'activity' => 'Magrib Prayer',
                'icon' => '🕌',
                'highlight' => false,
                'clue' => null,
            ],
            [
                'time' => '7:40 PM',
                'activity' => 'Fancy Dinner Time',
                'icon' => '🍽️',
                'highlight' => true,
                'clue' => null,
            ],
            [
                'time' => '9:50 PM',
                'activity' => 'Going back home',
                'icon' => '🏠',
                'highlight' => false,
                'clue' => null,
            ],
        ];

        // Try to find image
        $imageFiles = ['mimi.jpg', 'mimi.png', 'birthday-girl.jpg', 'birthday-girl.png'];
        foreach ($imageFiles as $imageFile) {
            $fullPath = Yii::getAlias('@webroot/images/birthday/') . $imageFile;
            if (file_exists($fullPath)) {
                $model->imagePath = Yii::getAlias('@web/images/birthday/') . $imageFile;
                break;
            }
        }

        return $model;
    }

    /**
     * Get schedule items as array
     * 
     * @return array
     */
    public function getScheduleItems()
    {
        return $this->schedule;
    }

    /**
     * Check if image exists
     * 
     * @return bool
     */
    public function hasImage()
    {
        return !empty($this->imagePath) && file_exists(
            str_replace(Yii::getAlias('@web'), Yii::getAlias('@webroot'), $this->imagePath)
        );
    }
}

