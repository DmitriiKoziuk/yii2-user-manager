<?php
namespace DmitriiKoziuk\yii2UserManager\data;

use yii\base\Component;
use DmitriiKoziuk\yii2UserManager\entities\User;
use DmitriiKoziuk\yii2UserManager\entities\UserProfile;
use DmitriiKoziuk\yii2UserManager\forms\UserInputForm;
use DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm;
use DmitriiKoziuk\yii2UserManager\services\UserStatusService;

final class UserData extends Component
{
    /**
     * @var User
     */
    private $_user;

    /**
     * @var UserProfile
     */
    private $_userProfile;

    /**
     * @var UserStatusService
     */
    private $_userStatusService;

    public function __construct(
        User $user,
        UserProfile $userProfile,
        UserStatusService $userStatusService
    ) {
        parent::__construct([]);
        $this->_user = $user;
        $this->_userProfile = $userProfile;
        $this->_userStatusService = $userStatusService;
    }

    public function getId()
    {
        return $this->_user->id;
    }

    public function getUsername()
    {
        return $this->_user->username;
    }

    public function getFirstName()
    {
        return $this->_userProfile->first_name;
    }

    public function getLastName()
    {
        return $this->_userProfile->last_name;
    }

    public function getEmail()
    {
        return $this->_user->email;
    }

    public function getStatus()
    {
        return $this->_userStatusService->getUserStatusName($this->_user);
    }

    public function getCreatedAt()
    {
        return $this->_user->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->_user->updated_at;
    }

    public function getUserUpdateForm()
    {
        $form = new UserInputForm(['scenario' => UserInputForm::SCENARIO_UPDATE]);
        $form->setAttributes($this->_user->getAttributes());
        return $form;
    }

    public function getUserProfileUpdateForm()
    {
        $form = new UserProfileInputForm();
        $form->setAttributes($this->_userProfile->getAttributes());
        return $form;
    }
}