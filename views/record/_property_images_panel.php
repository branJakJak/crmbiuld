<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 8:59 PM
 */
use derekisbusy\panel\PanelWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $propertyImagesDataProvider \yii\data\ActiveDataProvider */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $currentPropertyImage \app\models\PropertyImages */

$imageCollection =  [];

foreach($propertyRecord->getPropertyImages()->all() as $currentPropertyImage){
    $imageToPublish = Yii::getAlias('@upload_image_path') .  DIRECTORY_SEPARATOR.$currentPropertyImage->image_name;
    $published = $this->assetManager->publish($imageToPublish);
    $imageCollection[] = [
        'url' => $published[1],
        'src' => $published[1],
    ];
}

?>

<?php
echo PanelWidget::begin([
    'title'=>'Property Images',
    'type'=>'default',
    'widget'=>false,
])
?>
<?= dosamigos\gallery\Gallery::widget(['items' => $imageCollection]);?>

<?php
PanelWidget::end()
?>