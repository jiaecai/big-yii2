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
 * Class EasyWechatPayForm
 * @package common\forms
 */
class EasyWechatPayForm extends BaseForm
{

    /**
     * 微信支付相关：创建订单
     */
    public function createSingleWareOrder($wareId,$notifyUrl,$openId){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;
        $wareId;
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => '1217752501201407033233368018',
            'total_fee'        => 1, // 单位：分
            //通知url必须为直接可访问的url，不能携带参数。示例：
            'notify_url'       => $notifyUrl,//'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => $openId, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];

        //带有POST参数，XML格式
        $order = new Order($attributes);

        //公众号支付、扫码支付、APP 支付 都统一使用此接口完成订单的创建。
        try{
            $result = $payment->prepare($order);
            //echo "prepare成功";

            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
                $prepayId = $result->prepay_id;
                return $prepayId;
                //return $prepayId;
            }else{
                var_dump($result);//TODO 后面注销
                //die("出错了。");  // 出错就说出来，不然还能怎样？
            }
        }catch (\Exception $e){
            var_dump($e);
        }
        return null;
    }


    /**
     * 撤销订单
     */
    public function reverseOrder(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;

        $orderNo = "商户系统内部的订单号（out_trade_no）";
        $payment->reverse($orderNo);

        $orderNo = "微信的订单号（transaction_id）";
        $payment->reverseByTransactionId($orderNo);
    }

    /**
     * 查询订单
     */
    public function queryOrder(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;

        $orderNo = "商户系统内部的订单号（out_trade_no）";
        $payment->query($orderNo);

        $orderNo = "商户系统内部的订单号（out_trade_no）";
        $payment->query($orderNo);

    }

    /**
     * 关闭订单
     */
    public function closeOrder(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;

        $orderNo = "商户系统内部的订单号（out_trade_no）";
        $payment->query($orderNo);
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
