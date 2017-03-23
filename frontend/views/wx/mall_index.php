<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = '商城首页';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>商城首页</h1>

        <p class="lead">欢迎使用商城</p>

    </div>

    <div class="body-content">

        <?= Html::a('查看商品详情1', ['ware-detail', 'wareId' => 1], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('查看商品详情2', ['ware-detail', 'wareId' => 2], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('查看商品详情3', ['ware-detail', 'wareId' => 3], ['class' => 'btn btn-primary']) ?>

    </div>
</div>
