<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2UserManager;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use DmitriiKoziuk\yii2ConfigManager\services\ConfigService;
use DmitriiKoziuk\yii2ConfigManager\ConfigManagerModule;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleRegistrationService;

final class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function bootstrap($app)
    {
        ModuleRegistrationService::addModule(UserManager::class, function () {
            /** @var ConfigService $configService */
            $configService = Yii::$container->get(ConfigService::class);
            return [
                'class' => UserManager::class,
                'diContainer' => Yii::$container,
                'backendAppId' => $configService->getValue(
                    ConfigManagerModule::GENERAL_CONFIG_NAME,
                    'backendAppId'
                ),
            ];
        });
    }
}