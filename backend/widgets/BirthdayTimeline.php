<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * BirthdayTimeline Widget
 * 
 * Displays a timeline of birthday celebration schedule items
 * 
 * @package app\widgets
 */
class BirthdayTimeline extends Widget
{
    /**
     * @var array Schedule items to display
     */
    public $items = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        if (empty($this->items)) {
            $this->items = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('birthdayTimeline', [
            'items' => $this->items,
        ]);
    }
}

