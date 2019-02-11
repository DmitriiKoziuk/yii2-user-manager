<?php
namespace DmitriiKoziuk\yii2UserManager\services;

use yii\db\Connection;
use DmitriiKoziuk\yii2Base\exceptions\EntityNotFoundException;
use DmitriiKoziuk\yii2Base\services\DBActionService;
use DmitriiKoziuk\yii2Base\exceptions\DataNotValidException;
use DmitriiKoziuk\yii2UserManager\data\UserData;
use DmitriiKoziuk\yii2UserManager\entities\User;
use DmitriiKoziuk\yii2UserManager\entities\UserProfile;
use DmitriiKoziuk\yii2UserManager\exceptions\UserAlreadyExistException;
use DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm;
use DmitriiKoziuk\yii2UserManager\forms\UserInputForm;
use DmitriiKoziuk\yii2UserManager\repositories\UserProfileRepository;
use DmitriiKoziuk\yii2UserManager\repositories\UserRepository;

/**
 * Class UserActionService
 * @package DmitriiKoziuk\yii2UserManager\services
 */
final class UserActionService extends DBActionService
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
        UserStatusService $userStatusService,
        Connection $db = null
    ) {
        parent::__construct($db);
        $this->_userRepository = $userRepository;
        $this->_userProfileRepository = $userProfileRepository;
        $this->_userStatusService = $userStatusService;
    }

    /**
     * @param UserInputForm $userInputForm
     * @param UserProfileInputForm $userProfileInputForm
     * @return UserData
     * @throws DataNotValidException
     * @throws UserAlreadyExistException
     * @throws \DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException
     * @throws \Throwable
     */
    public function createUser(UserInputForm $userInputForm, UserProfileInputForm $userProfileInputForm): UserData
    {
        if (
            (! $userInputForm->validate() || $userInputForm->getScenario() != UserInputForm::SCENARIO_CREATE) ||
            ! $userProfileInputForm->validate()
        ) {
            throw new DataNotValidException('User data not valid.');
        }
        $user = $this->_userRepository->getByUsername($userInputForm->username);
        if (! empty($user)) {
            throw new UserAlreadyExistException("User with username '{$userInputForm->username}' already exist.");
        }
        $this->beginTransaction();
        try {
            $user = new User();
            $user->username = $userInputForm->username;
            $user->email = $userInputForm->email;
            $user->setPassword($userInputForm->password);
            $user->generateAuthKey();
            $this->_userRepository->save($user);
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
            $userProfile->first_name = $userProfileInputForm->first_name;
            $userProfile->last_name = $userProfileInputForm->last_name;
            $userProfile->middle_name = $userProfileInputForm->middle_name;
            $this->_userProfileRepository->save($userProfile);
            $this->commitTransaction();
            return new UserData($user, $userProfile, $this->_userStatusService);
        } catch (\Throwable $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @param UserInputForm $userInputForm
     * @param UserProfileInputForm $userProfileInputForm
     * @return UserData
     * @throws DataNotValidException
     * @throws \DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException
     * @throws \Throwable
     */
    public function updateUser(
        int $id,
        UserInputForm $userInputForm,
        UserProfileInputForm $userProfileInputForm
    ): UserData {
        if (
            (! $userInputForm->validate() || $userInputForm->getScenario() != UserInputForm::SCENARIO_UPDATE) ||
            ! $userProfileInputForm->validate()
        ) {
            throw new DataNotValidException('User data not valid.');
        }
        $this->beginTransaction();
        try {
            $user = $this->_userRepository->getById($id);
            if (empty($user)) {
                throw new EntityNotFoundException("User with username '{$userInputForm->username}' not found.");
            }
            $user->setAttributes($userInputForm->getAttributes());
            if (! empty($userInputForm->password)) {
                $user->setPassword($userInputForm->password);
                $user->generateAuthKey();
            }
            $this->_userRepository->save($user);
            $userProfile = $this->_userProfileRepository->getById($user->id);
            $userProfile->setAttributes($userProfileInputForm->getAttributes());
            $this->_userProfileRepository->save($userProfile);
            $this->commitTransaction();
            return new UserData($user, $userProfile, $this->_userStatusService);
        } catch (\Throwable $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     * @throws \DmitriiKoziuk\yii2Base\exceptions\EntityNotValidException
     * @throws \DmitriiKoziuk\yii2Base\exceptions\EntitySaveException
     */
    public function deleteUserById(int $id): void
    {
        $user = $this->_userRepository->getById($id);
        if (empty($user)) {
            throw new EntityNotFoundException("User with id '{$id}' not found.");
        }
        $user->status = User::STATUS_DELETED;
        $this->_userRepository->save($user);
    }

    public function deleteUserByUsername(string $username): void
    {
        $user = $this->_userRepository->getByUsername($username);
        if (empty($user)) {
            throw new EntityNotFoundException("User with id '{$username}' not found.");
        }
        $user->status = User::STATUS_DELETED;
        $this->_userRepository->save($user);
    }

    /**
     * @param UserInputForm $userInputForm
     * @return User
     * @throws DataNotValidException
     * @throws EntityNotFoundException
     * @throws \DmitriiKoziuk\yii2Base\exceptions\EntitySaveException
     */
    public function changeUserPassword(UserInputForm $userInputForm): User
    {
        if (
            (! $userInputForm->validate() || $userInputForm->getScenario() != UserInputForm::SCENARIO_UPDATE)
        ) {
            throw new DataNotValidException('User data not valid.');
        }
        $user = $this->_userRepository->getByUsername($userInputForm->username);
        if (empty($user)) {
            throw new EntityNotFoundException("User with username '{$userInputForm->username}' not found.");
        }
        $user->setPassword($userInputForm->password);
        $user->generateAuthKey();
        $this->_userRepository->save($user);
        return $user;
    }

    public function getById(int $id): UserData
    {
        $user = $this->_userRepository->getById($id);
        if (empty($user)) {
            throw new EntityNotFoundException("User with id '{$id}' not found.");
        }
        $userProfile = $this->_userProfileRepository->getById($user->id);
        if (empty($userProfile)) {
            $userProfile = new UserProfile();
        }
        return new UserData($user, $userProfile, $this->_userStatusService);
    }
}