<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/25/2017
 * Time: 5:28 PM
 */

namespace app\components;


use Yii;
use yii\base\Component;

class DropdownItemRetriever extends Component
{
    public static function getItems()
    {
         $dropdownItems = Yii::$app->params['statusCollection'];
        if (!Yii::$app->user->can('Admin') &&
            !Yii::$app->user->can('Senior Manager') &&
            !Yii::$app->user->can('admin')
        ) {
            // Dropdown items , remove Passed to Solicitor and Full Survey Complete
            unset($dropdownItems['Full Survey Complete']);
            unset($dropdownItems['Passed To Solicitor']);
        }
        return $dropdownItems;
    }
}