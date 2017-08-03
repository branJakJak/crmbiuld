<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Triages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="triage-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Triage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'material_file_name',
            'property_record',
            'date_created',
            'date_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
