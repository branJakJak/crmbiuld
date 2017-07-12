<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 9:00 PM
 */
use derekisbusy\panel\PanelWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $currentModel \app\models\PropertyNotes*/
/* @var $creator \dektrium\user\models\User */
/* @var $creatorProfile \dektrium\user\models\Profile */

?>


<?php
echo PanelWidget::begin([
    'title'=>'Notes History',
    'type'=>'default',
    'widget'=>false,
])
?>

<?= $this->render('/property-notes/_form',['model'=>$propertyNote])?>
<br>
<?=
    \yii\grid\GridView::widget([
        'dataProvider'=>$propertyNotesDataProvider,
        'columns'=>[
            'content',
            [
                'label'=>'Created by',
                'value'=>function($currentModel){
                    $creator = $currentModel->getCreator()->one();
                    $creatorProfile = $creator->getProfile()->one();
                    return $creatorProfile->name;
                },
            ],
            'date_created:datetime',
        ]
    ])
?>

<?php
PanelWidget::end()
?>