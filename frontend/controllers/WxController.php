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
class WxController extends Controller
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
     * 服务器验证
     * 无需页面
     */
    public function actionServVali(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $response = $app->server->serve();//执行服务端业务
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }


    /**
     * 用户消息处理
     * 总入口
     */
    public function actionHandle(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $server = $app->server;
        //$userService   = $app->user;
        //$oauth  = $app->oauth;

        $server->setMessageHandler(function ($message) {
            $message->ToUserName;    #接收方帐号（该公众号 ID）
            $openId=$message->FromUserName;  # 发送方帐号（OpenID, 代表用户的唯一标识）
            $message->CreateTime;    #消息创建时间（时间戳）
            $message->MsgId;         #消息 ID（64位整型）

            $app = new Application(Yii::$app->params['WECHAT']);
            $userService   = $app->user;
            $user = $userService->get($openId);
            echo $user['nickname'];
            $user->nickname;
            $user->get('nickname');
            //修改用户备注
            //$userService->remark($openId, $remark); // 成功返回boolean

            switch ($message->MsgType) {// 消息类型：event, text....
                case 'event':{
                    switch ($message->Event) {
                        case 'subscribe':{//订阅
                            # code...
                            $text = new Text(['content' => '欢迎订阅BIG-YII2!'.$message->Event]);
                            return $text;
                        }
                        case "unsubscribe":{//取消订阅
                            break;
                        }
                        case "location":{//上报位置
                            # 上报地理位置事件
                            $message->Latitude;    #23.137466   地理位置纬度
                            $message->Longitude;   #113.352425  地理位置经度
                            $message->Precision;   #119.385040  地理位置精度
                            break;
                        }
                        case "click":{//单击
                            switch ($message->EventKey) {
                                case "click":{
                                    $text = new Text(['content' => '您触发了点击事件']);
                                    return $text;
                                    break;
                                }
                                case "event_demo":{
                                    //图文消息
                                    /*
                                    $news = new News([
                                        'title'       => $title,
                                        'description' => '...',
                                        'url'         => $url,
                                        'image'       => $image,
                                        // ...
                                    ]);
                                    return $news;
                                    */
                                    //return [$news1, $news2, $news3, $news4];
                                    break;
                                }
                                case "fasongtuwen":{
                                    /*
                                    //文章消息
                                    - title 标题
                                    - author 作者
                                    - content 具体内容
                                    - thumb_media_id 图文消息的封面图片素材id（必须是永久mediaID）
- digest 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
                                    - source_url 来源 URL
                                    - show_cover 是否显示封面，0 为 false，即不显示，1 为 true，即显示

                                    $article = new Article([
                                        'title'   => 'EasyWeChat',
                                        'author'  => 'overtrue',
                                        'content' => 'EasyWeChat 是一个开源的微信 SDK，它... ...',
                                        // ...
                                    ]);

                                    //素材消息
                                    $material = new Material('mpnews', $mediaId);
                                    */
                                    break;
                                }
                                default:{
                                    break;
                                }
                            }
                            break;
                        }
                        case "scancode_waitmsg":{//扫码返回
                            # 扫描带参数二维码事件
                            //$message->EventKey;    #事件KEY值，比如：qrscene_123123，qrscene_为前缀，后面为二维码的参数值
                            $scanResult=$message->ScanCodeInfo->ScanResult;
                            switch ($message->EventKey){
                                case "scan_qr":{
                                    $text = new Text(['content' => $scanResult]);
                                    return $text;
                                    break;
                                }
                                default:break;
                            }
                            $message->Ticket;      #二维码的 ticket，可用来换取二维码图片
                            break;
                        }
                        default:{
                            # code...
                            $text = new Text(['content' => '未知的事件类型：'.$message->Event]);
                            return $text;
                            break;
                        }
                    }
                    return '收到事件消息';
                    break;
                }
                case 'text':
                    switch ($message->Content){
                        case "关键词1":{
                            $text = new Text(['content' => '您好！overtrue。我们已经收到您的消息']);
                            return $text;
                            break;
                        }
                        case "关键词2":{
                            break;
                        }
                        default:{//其他消息都通过多客服消息转发
                            $transfer = new \EasyWeChat\Message\Transfer();
                            //转发给指定客服
                            //$transfer->account($account);// 或者 $transfer->to($account);
                            return $transfer;
                        }
                    }
                    break;
                case 'image':{
                    $message->PicUrl;   #图片链接
                    //$text = new Image(['media_id' => $mediaId]);

                    //return '收到图片消息';
                    break;
                }
                case 'voice':
                    $message->MediaId;        #语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->Format;         #语音格式，如 amr，speex 等
                    $message->Recognition;    #* 开通语音识别后才有
                    //$voice = new Voice(['media_id' => $mediaId]);
                    return '收到语音消息';
                    break;
                case 'video':{
                    $message->MediaId;       #视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->ThumbMediaId;  #视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    /*
                    $video = new Video([
                        'title' => $title,
                        'media_id' => $mediaId,
                        'description' => '...',
                        'thumb_media_id' => $thumb
                    ]);
                    */
                    return '收到视频消息';
                    break;
                }
                case 'shortvideo':{
                    $message->MediaId;     //视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->ThumbMediaId;//    视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    return '收到小视频消息';
                    break;
                }
                case 'location':{
                    $message->Location_X;  //地理位置纬度
                    $message->Location_Y;  #地理位置经度
                    $message->Scale;       #地图缩放大小
                    $message->Label;       #地理位置信息
                //微信不支持回复位置消息
                    return '收到坐标消息';
                    break;
                }
                case 'link':{
                    $message->Title;        #消息标题
                    $message->Description;  #消息描述
                    $message->Url;          #消息链接
                //微信不支持回复链接消息
                    return '收到链接消息';
                    break;
                }
                // ... 其它消息
                default:{
                    return '收到其它消息';
                    break;
                }
            }
            return true;
        });
        $response = $server->serve();
        //$response->send(); // Laravel 里请使用：return $response;
        return $response;
    }


    /**
     *
     */
    public function actionOauth(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect();
        $response->send();
        $user = $app->oauth->user();
// $user 可以用的方法:
// $user->getId();  // 对应微信的 OPENID
// $user->getNickname(); // 对应微信的 nickname
// $user->getName(); // 对应微信的 nickname
// $user->getAvatar(); // 头像网址
// $user->getOriginal(); // 原始API返回的结果
// $user->getToken(); // access_token， 比如用于地址共享时使用

    }




    /**
     * 需要授权的页面
     */
    public function actionPageNeedOauth(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $oauth = $app->oauth;
        // 未登录
        if (empty($_SESSION['wechat_user'])) {
            $_SESSION['target_url'] = 'user/profile';//需要授权的页面
            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }
        // 已经登录过
        $user = $_SESSION['wechat_user'];
        // ...
        return $this->redirect('index');
    }

    /**
     * 网页授权回调页
     */
    public function actionOauthCallback(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $_SESSION['wechat_user'] = $user->toArray();
        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        header('location:'. $targetUrl); // 跳转到 user/profile
    }


    /**
     * 0、商城首页，
     * @return string
     */
    public function actionMallIndex(){
        $wareList=array();
        return $this->render('mall_index', [
            'wareList' => $wareList,
        ]);
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
        $userId="缓存的";
        //todo 带有订单和用户身份，等待用户下单，有下单按钮，点完后到达创建订单页面

        $app = new Application(Yii::$app->params['WECHAT']);

        $easyWechatPayForm = new EasyWechatPayForm();
        $prepayId=$easyWechatPayForm->createSingleWareOrder($wareId,'url','openId');//蛋类商品下单

        echo "here";
        exit;
        $payment = $app->payment;
        var_dump($payment);
        exit;
        $config = $payment->configForJSSDKPayment($prepayId);
        // 这个方法是取得js里支付所必须的参数用的。 没这个啥也做不了，除非你自己把js的参数生成一遍
        $js = $app->js;

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
        $response = $app->payment->handleNotify(function($notify, $successful){
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
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 'paid';
            } else { // 用户支付失败
                $order->status = 'paid_fail';
            }
            $order->save(); // 保存订单

            return true; // 或者错误消息，这里表示是否处理完成
        });
        $response->send(); // Laravel 里请使用：return $response;
        return $response;
    }


}
