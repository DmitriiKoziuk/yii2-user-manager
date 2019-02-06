<?php
namespace DmitriiKoziuk\yii2UserManager\entities;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%dk_user_profiles}}".
 *
 * @property integer $user_id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $middle_name
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dk_user_profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name'], 'required'],
            [['user_id'], 'integer'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 45],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User ID',
            'first_name'  => 'First name',
            'last_name'   => 'Last name',
            'middle_name' => 'Middle name',
        ];
    }
}
