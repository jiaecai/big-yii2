<?php

/* @var $this yii\web\View */

$this->title = '账户充值';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div>
            <div>
                <form name="form1" action="payment.php" method="post">
                    <lable>数量：</lable>
                    <input name="qty" value="1" />
                    <input name="submit" type="submit" value="确认支付" />
                </form>
            </div>
        </div>

    </div>
</div>


