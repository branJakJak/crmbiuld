<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserCreator */

$this->title = 'Create User Creator';
$this->params['breadcrumbs'][] = ['label' => 'User Creators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-creator-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
