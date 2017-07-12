<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kevin
 * Date: 12/15/15
 * Time: 4:39 AM
 * To change this template use File | Settings | File Templates.
 */

namespace app\assets;
use yii\web\AssetBundle;

class DashGumAssetBundle extends AssetBundle{
    public $sourcePath = '@app/assets/dashGumTheme';
    public $css = [
        'font-awesome/css/font-awesome.css',
        'css/zabuto_calendar.css',
        'js/gritter/css/jquery.gritter.css',
        'lineicons/style.css',
        'css/style-responsive.css',
        'css/style.css'
    ];
    public $js = [
        "js/chart-master/Chart.js",
        "js/jquery.dcjqaccordion.2.7.js",
        "js/jquery.scrollTo.min.js",
        "js/jquery.nicescroll.js",
        "js/jquery.sparkline.js",
        "js/common-scripts.js",
        "js/gritter/js/jquery.gritter.js",
        "js/gritter-conf.js",
        "js/sparkline-chart.js",
        "js/zabuto_calendar.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}