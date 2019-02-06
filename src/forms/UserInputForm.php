<?php
namespace DmitriiKoziuk\yii2UserManager\forms;

use DmitriiKoziuk\yii2Base\data\Data;

final class UserInputForm extends Data
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $username  = '';
    public $email = '';
    public $password  = '';

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required', 'on' => self::SCENARIO_CREATE],
            [['username', 'email'], 'required', 'on' => self::SCENARIO_UPDATE],
            [
                ['username', 'email'],
                'string',
                'min' => 1,
                'max' => 255,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [
                ['password'],
                'string',
                'min' => 6,
                'max' => 12,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ]
        ];
    }

    public function clearPassword(): void
    {
        $this->password = '';
    }
}