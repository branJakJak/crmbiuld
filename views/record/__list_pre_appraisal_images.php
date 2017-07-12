<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/11/2017
 * Time: 7:09 PM
 */
/* @var $model \app\models\PropertyPreAppraisalImages */


/*publish the image*/
$publishedImageUrl = '//placehold.it/150x150';
$uploadImagePath = Yii::getAlias("@upload_image_path") . DIRECTORY_SEPARATOR.$model->image_name;
/*get the url of published image*/
$publishedImageUrl = Yii::$app->assetManager->publish($uploadImagePath);

/*show the image*/
?>
<div class="col-lg-4" style="min-height: 225px">
    <img src="<?= $publishedImageUrl[1]?>" class="img-responsive" alt="Image">
    <small><?= $model->image_description ?></small>
</div>

