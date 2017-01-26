<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 12.01.2017
 * Time: 20:50
 */

namespace lesha724\grid;
use yii\web\AssetBundle;

class NLGridAsset extends AssetBundle
{

    public $sourcePath = '@lesha724/grid/assets';
    public $css = [
        'css/style.css'
    ];
    public $js = [
        //'js/bootstrap-add-clear.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}