<?php
/**
* Write Asset for Echarts.
*/

namespace app\assets;

use yii\web\AssetBundle;

class EchartsAsset extends AssetBundle
{
    //public $sourcePath = '@bower/echarts/build/dist';
    public $sourcePath = '@bower/echarts/dist';
    public $js = [
        'echarts.js',
    ];
}