<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Video;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;
use EasyWeChat\Support\Collection;
use EasyWeChat\Message\Material;
use common\forms\EasyWechatPayForm;

/**
 * Site controller
 */
class WxPayController extends Controller
{
    /**
     * @inheritdoc
     */
    /*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    */

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * 0、商品列表
     * @return string
     */
    public function actionMallIndex(){

        $ret=\common\forms\EasyWechatForm::quickOauth("wx-pay/mall-index");

        if($ret){
            // 已经登录过
            $userArray = json_decode($_COOKIE['wechat_user'],true);
            // ...
            $wareList=array();
            return $this->render('mall_index', [
                'wareList' => $wareList,
                'openId' => $userArray['id'],
            ]);
        }else{
            exit;
        }


        //todo 购物车功能？？暂时不提供
    }


    /**
     * 1、商品详情
     */
    public function actionWareDetail($wareId){
        //$orderId=
        return $this->render('ware_detail', [
            'wareId' => $wareId,
        ]);
    }

    /**
     * 1、充值
     */
    public function actionRecharge(){
        return $this->render('recharge', [
            //'wareId' => $wareId,
        ]);
    }

    /**
     * 2、充值或支付确认
     */
    public function actionPayConfirm($wareId=null,$orderId=null,$rechargeAmount=0){
        //todo 带有订单和用户身份，等待用户下单，有下单按钮，点完后到达创建订单页面

        $userArray = json_decode($_COOKIE['wechat_user'],true);

        $app = new Application(Yii::$app->params['WECHAT']);
        $payment = $app->payment;
        $js = $app->js;

        $easyWechatPayForm = new EasyWechatPayForm();

        $url="https://www.baidu.com";
        $prepayId=$easyWechatPayForm->createSingleWareOrder($wareId,$url,$userArray['id']);//蛋类商品下单

        //$json = $payment->configForPayment($prepayId);
        if(!$prepayId){//支付失败
            return $this->render('error', [
                'name' => '微信支付错误',
                'message' => '微信支付错误',
            ]);
        }

        $config = $payment->configForJSSDKPayment($prepayId);
        // 这个方法是取得js里支付所必须的参数用的。 没这个啥也做不了，除非你自己把js的参数生成一遍

        return $this->render('pay_confirm', [
            'config' => $config,
            'js' => $js,
            'wareId' => $wareId,
        ]);
    }


    /**
     * 微信支付回调
     */
    public function actionPayCallback(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $result=array();
        $response = $app->payment->handleNotify(function($notify, $successful) use ($result){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            /*
            $order = 查询订单($notify->out_trade_no);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            */
            $result="a";
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                //$order->status = 'paid';
                $result="支付成功！";

            } else { // 用户支付失败
                //$order->status = 'paid_fail';
                $result="支付成功！";
            }
            $result="a";

            //$order->save(); // 保存订单

            return true; // 或者错误消息，这里表示是否处理完成
        });
        $response->send(); // Laravel 里请使用：return $response;
        /*
        return $this->render('pay_result', [
            'result'=>$result
            //'config' => $config,
            //'js' => $js,
            //'wareId' => $wareId,
        ]);
        */
        //return $response;
    }


}
