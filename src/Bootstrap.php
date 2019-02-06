<?php
namespace DmitriiKoziuk\yii2UserManager;

use Yii;
use yii\base\BootstrapInterface;
use DmitriiKoziuk\yii2ConfigManager\services\ConfigService;
use DmitriiKoziuk\yii2ConfigManager\ConfigManager as ConfigModule;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleService;

final class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function bootstrap($app)
    {
        /** @var ConfigService $configService */
        $configService = Yii::$container->get(ConfigService::class);
        $app->setModule(UserManager::ID, [
            'class' => UserManager::class,
            'diContainer' => Yii::$container,
            'backendAppId' => $configService->getValue(
                ConfigModule::GENERAL_CONFIG_NAME,
                'backendAppId'
            ),
        ]);
        /** @var UserManager $module */
        $module = $app->getModule(UserManager::ID);
        /** @var ModuleService $moduleService */
        $moduleService = Yii::$container->get(ModuleService::class);
        $moduleService->registerModule($module);
    }
}