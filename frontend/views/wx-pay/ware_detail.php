<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = '商品详情';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>商品详情：<?=$wareId;?></h1>

        <p class="lead">商品描述</p>

    </div>

    <div class="body-content">

        <?= Html::a('确认支付', ['pay-confirm', 'wareId' => $wareId], ['class' => 'btn btn-primary']) ?>

    </div>
</div>


