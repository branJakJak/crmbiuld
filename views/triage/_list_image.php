<?php
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

