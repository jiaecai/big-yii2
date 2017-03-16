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
use EasyWeChat\Message\Material;

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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 服务器验证
     */
    public function actionServVali(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $response = $app->server->serve();//执行服务端业务
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }


    /**
     * 用户消息处理
     */
    public function actionHandle(){
        $app = new Application(Yii::$app->params['WECHAT']);
        $server = $app->server;
        $userService   = $app->user;
        $oauth  = $app->oauth;
        $user = $userService->get($openId);

        $user['nickname'];
        $user->nickname;
        $user->get('nickname');

        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            $message->ToUserName;    #接收方帐号（该公众号 ID）
            $openId=$message->FromUserName;  # 发送方帐号（OpenID, 代表用户的唯一标识）
            $message->CreateTime;    #消息创建时间（时间戳）
            $message->MsgId;         #消息 ID（64位整型）


            switch ($message->MsgType) {
                case 'event':

                    switch ($message->Event) {
                        case 'subscribe':
                            # code...
                            break;
                        default:
                            # code...
                            break;
                    }

                    $message->Event       事件类型 （如：subscribe(订阅)、unsubscribe(取消订阅) ...， CLICK 等）

                    # 扫描带参数二维码事件
                    $message->EventKey    事件KEY值，比如：qrscene_123123，qrscene_为前缀，后面为二维码的参数值
                    $message->Ticket      二维码的 ticket，可用来换取二维码图片

                    # 上报地理位置事件
                    $message->Latitude    23.137466   地理位置纬度
                    $message->Longitude   113.352425  地理位置经度
                    $message->Precision   119.385040  地理位置精度

                    # 自定义菜单事件
                    $message->EventKey    事件KEY值，与自定义菜单接口中KEY值对应，如：CUSTOM_KEY_001, www.qq.com


                //图文消息
                $news = new News([
                    'title'       => $title,
                    'description' => '...',
                    'url'         => $url,
                    'image'       => $image,
                    // ...
                ]);
                    return [$news1, $news2, $news3, $news4];

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

                    return '收到事件消息';

                    break;
                case 'text':
                    $message->Content  文本消息内容
                    $text = new Text(['content' => '您好！overtrue。']);
                    return '收到文字消息';
                    break;
                case 'image':
                    $message->PicUrl   图片链接
                    $text = new Image(['media_id' => $mediaId]);
                    return '收到图片消息';
                    break;
                case 'voice':
                    $message->MediaId        语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->Format         语音格式，如 amr，speex 等
                    $message->Recognition * 开通语音识别后才有
                    $voice = new Voice(['media_id' => $mediaId]);
                    return '收到语音消息';
                    break;
                case 'video':
                    $message->MediaId       视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->ThumbMediaId  视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    $video = new Video([
                        'title' => $title,
                        'media_id' => $mediaId,
                        'description' => '...',
                        'thumb_media_id' => $thumb
                    ]);

                    return '收到视频消息';
                    break;
                case 'shortvideo':
                    $message->MediaId     视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $message->ThumbMediaId    视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    return '收到小视频消息';
                    break;
                case 'location':
                    $message->Location_X  地理位置纬度
                    $message->Location_Y  地理位置经度
                    $message->Scale       地图缩放大小
                    $message->Label       地理位置信息
                //微信不支持回复位置消息
                    return '收到坐标消息';
                    break;
                case 'link':
                    $message->Title        消息标题
                    $message->Description  消息描述
                    $message->Url          消息链接
                //微信不支持回复链接消息
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
            return "您好！欢迎关注我!";
        });
        $response = $server->serve();
        $response->send(); // Laravel 里请使用：return $response;

    }


    /**
     * 常用示例
     */
    public function actionDemo(){
        $app = new Application(Yii::$app->params['WECHAT']);

        /**
         * 用户信息获取
         */
        $userService = $app->user;
        $user = $userService->get($openId);
        echo $user->nickname;

        //修改用户备注
        $userService->remark($openId, $remark); // 成功返回boolean


        /**
         * 发送模板消息
         */
        $notice = $app->notice;
        $userId = 'OPENID';
        $templateId = 'ngqIpbwh8bUfcSsECmogfXcV14J0tQlEpBO27izEYtY';
        $url = 'http://overtrue.me';
        $data = array(
            "first"    => array("恭喜你购买成功！", '#555555'),
            "keynote1" => array("巧克力", "#336699"),
            "keynote2" => array("39.8元", "#FF0000"),
            "keynote3" => array("2014年9月16日", "#888888"),
            "remark"   => array("欢迎再次购买！", "#5599FF"),
        );
        $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        var_dump($result);

        /**
         * 群发消息
         */
        $broadcast = $app->broadcast;

        //群发消息给指定用户
        $broadcast->send($messageType, $message, [$openId1, $openId2]);
// 别名方式
        $broadcast->sendText($text, [$openId1, $openId2]);
        $broadcast->sendNews($mediaId, [$openId1, $openId2]);
        $broadcast->sendVoice($mediaId, [$openId1, $openId2]);
        $broadcast->sendImage($mediaId, [$openId1, $openId2]);
        $broadcast->sendVideo($message, [$openId1, $openId2]);
        $broadcast->sendCard($cardId, [$openId1, $openId2]);
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
     *
     */
    public function actionServerVali(){
        // 微信网页授权:
        if(Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }

        /*
        $server = $app->server;
        $oauth  = $app->oauth;
        // ...
        $userService = $app->user; // 用户API
        $user = $userService->get($openId);
        // $user 便是一个 EasyWeChat\Support\Collection 实例
        $user['nickname'];
        $user->nickname;
        $user->get('nickname');
        $app->server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!";
        });

        */
    }

}
