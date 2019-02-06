<?php
namespace DmitriiKoziuk\yii2UserManager\forms;

use yii\base\Model;

final class UserProfileInputForm extends Model
{
    public $first_name  = '';
    public $last_name   = '';
    public $middle_name = '';

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'trim'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'min' => 1, 'max' => 45],
        ];
    }
}