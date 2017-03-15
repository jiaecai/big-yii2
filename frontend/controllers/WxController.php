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
        */

        $app->server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!";
        });
        $response = $app->server->serve();//执行服务端业务
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }


    /**
     *
     */
    public function actionServerVali(){
        // 微信网页授权:
        if(Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }
    }

}
