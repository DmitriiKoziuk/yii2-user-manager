<?php
namespace DmitriiKoziuk\yii2UserManager\repositories;

use DmitriiKoziuk\yii2Base\repositories\EntityRepository;
use DmitriiKoziuk\yii2UserManager\entities\UserProfile;

final class UserProfileRepository extends EntityRepository
{
    public function getById(int $userId): UserProfile
    {
        /** @var UserProfile $userProfile */
        $userProfile = UserProfile::find()->where(['user_id' => $userId])->one();
        if (empty($userProfile)) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $userId;
        }
        return $userProfile;
    }
}