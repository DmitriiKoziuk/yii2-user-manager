<?php
namespace DmitriiKoziuk\yii2UserManager\services;

use yii\db\Connection;
use DmitriiKoziuk\yii2Base\services\DBActionService;
use DmitriiKoziuk\yii2UserManager\entities\User;
use DmitriiKoziuk\yii2UserManager\entities\UserProfile;
use DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm;
use DmitriiKoziuk\yii2UserManager\repositories\UserRepository;
use DmitriiKoziuk\yii2UserManager\repositories\UserProfileRepository;

final class UserProfileService extends DBActionService
{
    /**
     * @var UserProfileRepository
     */
    private $_userProfileRepository;

    /**
     * @var UserRepository
     */
    private $_userRepository;

    /**
     * @var UserActionService
     */
    private $_userService;

    public function __construct(
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        UserActionService $userService,
        Connection $db = null
    ) {
        parent::__construct($db);
        $this->_userService = $userService;
        $this->_userProfileRepository = $userProfileRepository;
        $this->_userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @param UserProfileInputForm $userProfileForm
     * @return User
     * @throws \Exception
     */
    public function update(
        int $userId,
        UserProfileInputForm $userProfileForm
    ): User {
        try {
            /** @var \DmitriiKoziuk\yii2UserManager\entities\User $user */
            $user = $this->_userRepository->getById($userId);
            if (empty($user->profile)) {
                $userProfile = new UserProfile();
                $userProfile->user_id = $user->id;
            } else {
                $userProfile = $user->profile;
            }
            $userProfile->setAttributes($userProfileForm->getAttributes());
            $this->_userProfileRepository->save($userProfile);
            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete()
    {
        //TODO make delete() method.
    }
}