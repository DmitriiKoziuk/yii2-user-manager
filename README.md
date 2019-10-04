Yii2 user manager
========================
Yii2 user manager

##Info

The best practice is use this module/extension with [yii2 advanced application](https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/start-installation.md)

## Preparing application

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

    Either run
    
    ```
    php composer.phar require dmitriikoziuk/yii2-user-manager
    ```

2. Create a new database and adjust the `components['db']` configuration in `/path/to/yii-application/common/config/main-local.php` accordingly.

3. Open a console terminal, apply migrations with command `/path/to/php-bin/php /path/to/yii-application/yii migrate`.

4. Run command `/path/to/php-bin/php /path/to/yii-application/yii migrate`

##Usage

Create user form console

```
php yii dk-user-manager/user/create Username password [email]
```

Delete user form console

```
php yii dk-user-manager/user/delete Username
```