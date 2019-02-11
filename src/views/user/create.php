<?php

use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $userInputForm \DmitriiKoziuk\yii2UserManager\forms\UserInputForm
 * @var $userProfileInputForm \DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm
 */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'userInputForm' => $userInputForm,
        'userProfileInputForm' => $userProfileInputForm,
    ]) ?>

</div>
