<?php

/* @var $this yii\web\View */

use app\assets\EchartsAsset;
use Hisune\EchartsPHP\ECharts;

$asset=EchartsAsset::register($this);
$chart = new ECharts($asset->baseUrl);
$chart->tooltip->show = true;
$chart->legend->data = array('销量');
$chart->xAxis = array(
    array(
        'type' => 'category',
        'data' => array("衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子")
    )
);
$chart->yAxis = array(
    array('type' => 'value')
);
$chart->series = array(
    array(
        'name' => '销量',
        'type' => 'bar',
        'data' => array(5, 20, 40, 10, 10, 20)
    )
);
echo $chart->render('simple-custom-id');