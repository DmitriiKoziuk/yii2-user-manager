<?php
namespace DmitriiKoziuk\yii2UserManager\services;

use DmitriiKoziuk\yii2UserManager\data\UserSearchParams;
use DmitriiKoziuk\yii2UserManager\data\UserData;
use DmitriiKoziuk\yii2UserManager\data\UserDataProvider;
use DmitriiKoziuk\yii2UserManager\entities\User;
use DmitriiKoziuk\yii2UserManager\repositories\UserProfileRepository;
use DmitriiKoziuk\yii2UserManager\repositories\UserRepository;

final class UserSearchService
{
    /**
     * @var UserRepository
     */
    private $_userRepository;

    /**
     * @var UserProfileRepository
     */
    private $_userProfileRepository;

    /**
     * @var UserStatusService
     */
    private $_userStatusService;

    public function __construct(
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        UserStatusService $userStatusService
    ) {
        $this->_userRepository = $userRepository;
        $this->_userProfileRepository = $userProfileRepository;
        $this->_userStatusService = $userStatusService;
    }

    public function searchBy(UserSearchParams $searchParams): UserDataProvider
    {
        $activeDataProvider = $this->_userRepository->search($searchParams);
        $users = [];
        foreach ($activeDataProvider->getModels() as $user) {
            /** @var User $user */
            $userProfile = $this->_userProfileRepository->getById($user->id);
            $users[] = new UserData($user, $userProfile, $this->_userStatusService);
        }
        return new UserDataProvider($users, $activeDataProvider);
    }
}