<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class About extends ActiveRecord
{
    public static function tableName()
    {
        return 'about';
    }

    public function rules()
    {
        return [
            [['title', 'content', 'ceo_name', 'ceo_title', 'ceo_image'], 'required'],
            [['title', 'ceo_name', 'ceo_title'], 'string', 'max' => 255],
            [['content', 'ceo_image'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'ceo_name' => 'CEO Name',
            'ceo_title' => 'CEO Title',
            'ceo_image' => 'CEO Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
