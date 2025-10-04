<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Download extends ActiveRecord
{
    public static function tableName()
    {
        return 'downloads';
    }

    public function rules()
    {
        return [
            [['brand_name', 'download_url'], 'required'],
            [['brand_name'], 'string', 'max' => 255],
            [['download_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_name' => 'Brand Name',
            'download_url' => 'Download URL',
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
