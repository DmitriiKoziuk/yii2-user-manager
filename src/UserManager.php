<?php
namespace DmitriiKoziuk\yii2UserManager;

use yii\di\Container;
use yii\web\Application as WebApp;
use yii\base\Application as BaseApp;
use yii\console\Application as ConsoleApp;
use DmitriiKoziuk\yii2Base\BaseModule;
use DmitriiKoziuk\yii2ModuleManager\interfaces\ModuleInterface;
use DmitriiKoziuk\yii2ConfigManager\ConfigManagerModule;
use DmitriiKoziuk\yii2UserManager\services\UserActionService;
use DmitriiKoziuk\yii2UserManager\services\UserProfileService;
use DmitriiKoziuk\yii2UserManager\services\UserStatusService;
use DmitriiKoziuk\yii2UserManager\services\UserSearchService;
use DmitriiKoziuk\yii2UserManager\repositories\UserRepository;
use DmitriiKoziuk\yii2UserManager\repositories\UserProfileRepository;
use DmitriiKoziuk\yii2UserManager\entities\User;

final class UserManager extends \yii\base\Module implements ModuleInterface
{
    const ID = 'dk-user-manager';

    const TRANSLATE = self::ID;

    /**
     * @var Container
     */
    public $diContainer;

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $backendAppId;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        /** @var BaseApp $app */
        $app = $this->module;
        $this->_initLocalProperties($app);
        $this->_registerTranslation($app);
        $this->_registerClassesToDIContainer($app);
    }

    public static function getId(): string
    {
        return self::ID;
    }

    public function getBackendMenuItems(): array
    {
        return ['label' => 'User manager', 'url' => ['/' . self::ID . '/user/index']];
    }

    public static function requireOtherModulesToBeActive(): array
    {
        return [
            ConfigManagerModule::class,
        ];
    }

    /**
     * @param BaseApp $app
     */
    private function _initLocalProperties(BaseApp $app)
    {
        if (empty($this->backendAppId)) {
            throw new \InvalidArgumentException('Property backendAppId not set.');
        }
        if ($app instanceof WebApp && $app->id == $this->backendAppId) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\backend';
        }
        if ($app instanceof ConsoleApp) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\console';
            $app->controllerMap['migrate'] = [
                'class' => 'yii\console\controllers\MigrateController',
                'migrationPath' => null,
                'migrationNamespaces' => [
                    __NAMESPACE__ . '\migrations',
                ],
            ];
        }
    }

    private function _registerTranslation(BaseApp $app)
    {
        $app->i18n->translations[self::TRANSLATE] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@DmitriiKoziuk/yii2UserManager/messages',
        ];
    }

    /**
     * @param BaseApp $app
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function _registerClassesToDIContainer(BaseApp $app): void
    {
        $this->diContainer->setSingleton(UserRepository::class, function () {
            return new UserRepository(new User());
        });
        $this->diContainer->setSingleton(UserProfileRepository::class, function () {
            return new UserProfileRepository();
        });

        /** @var UserRepository $userRepository */
        $userRepository = $this->diContainer->get(UserRepository::class);
        /** @var UserProfileRepository $userProfileRepository */
        $userProfileRepository = $this->diContainer->get(UserProfileRepository::class);

        $this->diContainer->setSingleton(UserStatusService::class, function () {
            return new UserStatusService();
        });
        /** @var UserStatusService $userStatusService */
        $userStatusService = $this->diContainer->get(UserStatusService::class);

        $this->diContainer->setSingleton(
            UserActionService::class,
            function () use ($userRepository, $userProfileRepository, $userStatusService, $app) {
                return new UserActionService(
                    $userRepository,
                    $userProfileRepository,
                    $userStatusService,
                    $app->db
                );
            }
        );

        /** @var UserActionService $userActionService */
        $userActionService = $this->diContainer->get(UserActionService::class);

        $this->diContainer->setSingleton(
            UserProfileService::class,
            function () use ($userRepository, $userProfileRepository, $userActionService, $app) {
                return new UserProfileService(
                    $userRepository,
                    $userProfileRepository,
                    $userActionService,
                    $app->db
                );
            }
        );

        $this->diContainer->setSingleton(UserStatusService::class, function () {
            return new UserStatusService();
        });
        $this->diContainer->setSingleton(
            UserSearchService::class,
            function () use ($userRepository, $userProfileRepository, $userStatusService) {
                return new UserSearchService(
                    $userRepository,
                    $userProfileRepository,
                    $userStatusService
                );
            }
        );
    }
}