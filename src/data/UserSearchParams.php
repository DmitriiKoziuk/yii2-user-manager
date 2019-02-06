<?php
namespace DmitriiKoziuk\yii2UserManager\data;

final class UserSearchParams extends \yii\base\Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $createdAt;
    public $updatedAt;
    public $firstName;
    public $lastName;
    public $middleName;

    public function rules()
    {
        return [
            [['id', 'status', 'createdAt', 'updatedAt'], 'integer'],
            [['username', 'email'], 'string', 'max' => 255],
            [['firstName', 'lastName', 'middleName'], 'string', 'max' => 45],
        ];
    }
}