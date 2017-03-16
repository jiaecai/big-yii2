<?php
/**
 * Created by PhpStorm.
 * User: jiaecai
 * Date: 17/03/2017
 * Time: 00:03
 */

namespace common\forms;

use common\base\BaseForm;
use Yii;
use EasyWeChat\Payment\Order;
use EasyWeChat\Foundation\Application;

/**
 * Class QrCodeForm
 * @package common\forms
 */
class EasyWechatForm extends BaseForm
{

    /**
     * 创建订单
     */
    public function createOrder(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => '1217752501201407033233368018',
            'total_fee'        => 5388, // 单位：分
            'notify_url'       => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
    }

    /**
     * 支付结果通知
     */
    public function payResult(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 你的逻辑
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = 查询订单($notify->out_trade_no);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 'paid';
            } else { // 用户支付失败
                $order->status = 'paid_fail';
            }
            $order->save(); // 保存订单

            return true; // 或者错误消息
        });
        $response->send(); // Laravel 里请使用：return $response;
    }


    /**
     * 下载对账单
     */
    public function downloadBill(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;
        $bill = $payment->downloadBill('20140603')->getContents(); // type: ALL
// or
        $bill = $payment->downloadBill('20140603', 'SUCCESS')->getContents(); // type: SUCCESS
// bill 为 csv 格式的内容
// 保存为文件
        file_put_contents('YOUR/PATH/TO/bill-20140603.csv', $bill);
    }

}
