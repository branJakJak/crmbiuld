<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyDocuments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-documents-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->user->can('admin')): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'property_id',
            'document_name',
            'date_created',
            'date_updated',
        ],
    ]) ?>

</div>
