<?php
namespace DmitriiKoziuk\yii2UserManager\entities;

class User extends \common\models\User
{
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['username', 'email'], 'required'];
        $rules[] = [['username', 'email'], 'trim'];
        $rules[] = [['username'], 'string', 'min' => 1, 'max' => 255];
        $rules[] = [['email'], 'string', 'min' => 4, 'max' => 255];
        return $rules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }
}