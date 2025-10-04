<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "institutes".
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string $image_path
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Institute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'institutes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'abbreviation', 'image_path'], 'required'],
            [['name', 'abbreviation', 'image_path', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'abbreviation' => 'Abbreviation',
            'image_path' => 'Image Path',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
