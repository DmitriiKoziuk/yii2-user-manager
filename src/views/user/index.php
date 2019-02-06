<?php

use yii\helpers\Html;
use yii\grid\GridView;
use DmitriiKoziuk\yii2UserManager\services\UserStatusService;

/**
 * @var $this yii\web\View
 * @var $usersDataProvider \DmitriiKoziuk\yii2UserManager\data\UserDataProvider
 * @var $searchParams \DmitriiKoziuk\yii2UserManager\data\UserSearchParams
 * @var $userStatusService UserStatusService
 */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $usersDataProvider,
        'filterModel' => $searchParams,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'firstName',
            'lastName',
            'email:email',
            [
                'attribute' => 'status',
                'filter' => $userStatusService->getUserStatuses(),
            ],
            'createdAt:datetime',
            'updatedAt:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
