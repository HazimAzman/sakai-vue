<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class AuthToken extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%auth_tokens}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'jti', 'token_hash', 'expires_at'], 'required'],
            [['user_id'], 'integer'],
            [['jti'], 'string', 'max' => 64],
            [['token_hash'], 'string', 'max' => 64],
            [['expires_at', 'created_at'], 'safe'],
            [['jti'], 'unique'],
        ];
    }
}


