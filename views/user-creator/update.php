<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserCreator */

$this->title = 'Update User Creator: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Creators', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-creator-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
