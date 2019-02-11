<?php
namespace DmitriiKoziuk\yii2UserManager\controllers\console;

use yii\base\Module;
use yii\helpers\Console;
use yii\console\Controller;
use DmitriiKoziuk\yii2Base\exceptions\Exception;
use DmitriiKoziuk\yii2UserManager\forms\UserInputForm;
use DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm;
use DmitriiKoziuk\yii2UserManager\services\UserActionService;

final class UserController extends Controller
{
    /**
     * @var UserActionService
     */
    private $_userActionService;

    public function __construct(
        string $id,
        Module $module,
        UserActionService $userActionService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->_userActionService = $userActionService;
    }

    public function actionIndex()
    {
        $this->stdout('Hello' . PHP_EOL, Console::FG_GREEN);
        return 0;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string|null $email
     * @return int
     * @throws \Throwable
     */
    public function actionCreate(
        string $username,
        string $password,
        string $email = null
    ) {
        try {
            $userForm = new UserInputForm(['scenario' => UserInputForm::SCENARIO_CREATE]);
            $userForm->username = $username;
            $userForm->password = $password;
            $userForm->email = ! empty($email) ? $email : 'someFakeEmailAddress' . rand(1, 10000);

            $this->_userActionService->createUser($userForm, new UserProfileInputForm());
            $this->stdout("User with username '{$username}' created." . PHP_EOL, Console::FG_GREEN);

            return 0;
        } catch (Exception $e) {
            $this->stderr($e->getMessage() . PHP_EOL, Console::FG_RED);
            return 1;
        }
    }

    /**
     * @param string $username
     * @return int
     */
    public function actionDelete(string $username)
    {
        try {
            $this->_userActionService->deleteUserByUsername($username);
            $this->stdout("User with username '{$username}' deleted." . PHP_EOL, Console::FG_GREEN);
            return 0;
        } catch (Exception $e) {
            $this->stderr($e->getMessage() . PHP_EOL, Console::FG_RED);
            return 1;
        }
    }

    /**
     * @param string $userName
     * @param string $newPassword
     * @return int
     */
    public function actionChangePassword(string $userName, string $newPassword)
    {
        try {
            $form = new UserInputForm(['scenario' => UserInputForm::SCENARIO_UPDATE]);
            $form->username = $userName;
            $form->password = $newPassword;
            $this->_userActionService->changeUserPassword($form);
            $this->stdout("Password changed." . PHP_EOL, Console::FG_GREEN);
            return 0;
        } catch (Exception $e) {
            $this->stderr($e->getMessage() . PHP_EOL, Console::FG_RED);
            return 1;
        }
    }
}