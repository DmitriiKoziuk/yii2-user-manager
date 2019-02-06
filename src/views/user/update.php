<?php

use yii\helpers\Html;
use DmitriiKoziuk\yii2UserManager\UserManager;

/**
 * @var $this \yii\web\View
 * @var $id integer
 * @var $userUpdateForm \DmitriiKoziuk\yii2UserManager\forms\UserInputForm
 * @var $userProfileUpdateForm \DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm
 */

$this->title = Yii::t(UserManager::TRANSLATE, 'Update User: {name}', [
    'name' => $userUpdateForm->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(UserManager::TRANSLATE, 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userUpdateForm->username;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'userUpdateForm' => $userUpdateForm,
        'userProfileUpdateForm' => $userProfileUpdateForm,
    ]) ?>

</div>
