<?php
namespace DmitriiKoziuk\yii2UserManager\forms;

use yii\base\Model;

final class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [
                ['username', 'email'],
                'string',
                'min' => 1,
                'max' => 25,
            ],
            [
                ['password'],
                'string',
                'min' => 6,
                'max' => 25,
            ],
            ['email', 'email'],
            ['username', 'usernameFilters'],
        ];
    }

    public function usernameFilters($attribute)
    {
        $value = $this->username;
        if ('' === trim($value)) {
            $this->addError($attribute,'Username cannot contain only special characters.');
        }
        if ($value != ltrim($value)) {
            $this->addError($attribute,'Username can start only from character.');
        }
        if ($value != rtrim($value)) {
            $this->addError($attribute,'Username can end only by character.');
        }
    }
}