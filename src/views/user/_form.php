<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $userInputForm \DmitriiKoziuk\yii2UserManager\forms\UserInputForm
 * @var $userProfileInputForm \DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($userInputForm, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userInputForm, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userInputForm, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileInputForm, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileInputForm, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileInputForm, 'middle_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
