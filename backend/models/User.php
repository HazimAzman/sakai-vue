<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\services\SecurityService;

class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_MODERATOR = 'moderator';
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;

    public $password;
    public $password_repeat;
    public $current_password;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            [['username', 'email'], 'unique'],
            [['username'], 'string', 'min' => 3, 'max' => 50],
            [['email'], 'email'],
            [['password_hash'], 'string', 'min' => 60],
            [['role'], 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_MODERATOR]],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_BANNED]],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
            [['password'], 'string', 'min' => 8],
            [['password'], 'validatePasswordStrength'],
            [['last_login_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Repeat Password',
            'role' => 'Role',
            'status' => 'Status',
            'last_login_at' => 'Last Login',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function validatePasswordStrength($attribute, $params)
    {
        $securityService = new SecurityService();
        $result = $securityService->validatePasswordStrength($this->$attribute);
        
        if ($result !== true) {
            foreach ($result as $error) {
                $this->addError($attribute, $error);
            }
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // If a plain password was provided, hash it into password_hash
            if (!empty($this->password)) {
                $securityService = new SecurityService();
                $this->password_hash = $securityService->hashPassword($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $securityService = new SecurityService();
        try {
            $payload = $securityService->validateToken($token);
            // Enforce DB presence of token (payload.jti)
            if (empty($payload['jti'])) {
                return null;
            }
            $authTokenModel = \app\models\AuthToken::findOne(['jti' => $payload['jti']]);
            if (!$authTokenModel) {
                return null; // token not in DB -> treat as invalid
            }
            return static::findIdentity($payload['user_id']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $securityService = new SecurityService();
        return $securityService->verifyPassword($password, $this->password_hash);
    }

    /**
     * Generate new auth key
     */
    public function generateAuthKey()
    {
        $securityService = new SecurityService();
        $this->auth_key = $securityService->generateSecureToken();
    }

    /**
     * Update last login time
     */
    public function updateLastLogin()
    {
        $this->last_login_at = date('Y-m-d H:i:s');
        $this->save(false);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Check if user is moderator
     */
    public function isModerator()
    {
        return $this->hasRole(self::ROLE_MODERATOR);
    }

    /**
     * Get user roles hierarchy
     */
    public function getRoleHierarchy()
    {
        $hierarchy = [
            self::ROLE_USER => 1,
            self::ROLE_MODERATOR => 2,
            self::ROLE_ADMIN => 3,
        ];
        
        return $hierarchy[$this->role] ?? 0;
    }

    /**
     * Check if user has permission level
     */
    public function hasPermissionLevel($requiredLevel)
    {
        return $this->getRoleHierarchy() >= $requiredLevel;
    }
}
