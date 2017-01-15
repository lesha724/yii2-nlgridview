<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 12.01.2017
 * Time: 20:50
 */

namespace backend\components\Grid;
use yii\web\AssetBundle;

class NLGridAsset extends AssetBundle
{
    public $sourcePath = '@backend/components/Grid/assets';
    public $css = [
        //'css/responsive.css'
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}