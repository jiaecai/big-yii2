<?php
namespace console\controllers;

use Yii;
use common\base\BaseConsoleController;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Video;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Material;

/**
 * Site controller
 */
class TestController extends BaseConsoleController
{

    /**
     * 测试微信支付
     * @return string
     */
   public function actionWxPay(){
       //todo
       $userId="缓存的";
       //todo 带有订单和用户身份，等待用户下单，有下单按钮，点完后到达创建订单页面

       $app = new Application(Yii::$app->params['WECHAT']);
       $payment = $app->payment;
       $js = $app->js;

       $easyWechatPayForm = new \common\forms\EasyWechatPayForm();
       $url="https://www.baidu.com";
       $openId="o7C_1wK0Mo6PBJg4KaVrhPf68od8";//测试号用
       $openId="o8h3Rs6LrUVB-MuH4skeMtzEyRZ0";//生产号用
       $prepayId=$easyWechatPayForm->createSingleWareOrder('wareId',$url,$openId);//蛋类商品下单

       if($prepayId){
           $config = $payment->configForJSSDKPayment($prepayId);
           echo "prepare成功，生成配置";
           var_dump($config);
       }else{
           echo "prepare失败";
       }
   }

    /**
     * 短信测试样例
     */
   public function actionSendSms($recNum="18810359625"){
       $params=[
           'time' => "时间",
            'address' => "地点"
       ];
       $ret=\common\util\NotificationUtil::sendSms($recNum,"SMS_13191310",$params,'大鱼测试');
       var_dump($ret);
   }

    /**
     * 发送微信模板消息
     */
   public function actionSendWxTempMsg(){
       $openId="o8h3Rs6LrUVB-MuH4skeMtzEyRZ0";//我的生产号
       $tempId="mASH0i62CLxQLsWCizJIA3MWpn5-osHN0fmnoRpXNt0";//注册成功的模板
       //$url="https://www.baidu.com";
       $url=null;//"https://www.baidu.com";

       $dataArray=array();
       $dataArray['first']=array("first", '#555555');
       $dataArray['keyword1']="keyword1";
       $dataArray['keyword2']="keyword2";
       $dataArray['keyword3']="keyword3";

       $ret=\common\util\NotificationUtil::sendWxTempMsg($openId,$tempId,$dataArray,$url);
       var_dump($ret);

   }

}
