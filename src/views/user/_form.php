<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use DmitriiKoziuk\yii2Base\BaseModule as BaseModule;

/**
 * @var $this \yii\web\View
 * @var $userUpdateForm \DmitriiKoziuk\yii2UserManager\forms\UserInputForm
 * @var $userProfileUpdateForm \DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($userUpdateForm, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userUpdateForm, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userUpdateForm, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileUpdateForm, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileUpdateForm, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfileUpdateForm, 'middle_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t(BaseModule::TRANSLATE, 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
