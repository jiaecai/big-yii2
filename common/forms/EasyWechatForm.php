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
 * Class EasyWechatForm
 * @package common\forms
 */
class EasyWechatForm extends BaseForm
{


    /**
     * @param string $reqRoute
     * @return bool
     */
    public static function quickOauth($reqRoute="/"){
        $app = new Application(Yii::$app->params['WECHAT']);
        $oauth = $app->oauth;
        // 未登录
        if (!isset($_COOKIE['wechat_user'])) {
            //$_SESSION['target_url'] = Url::to(['wx/page-need-oauth']); //需要授权的页面
            setcookie('route',$reqRoute,time()+3600*24);
            $oauth->redirect()->send();
            return false;
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            //return $oauth->redirect();
        }else{
            return true;
        }
    }

    /**
     * 发送模板消息
     * @param $openId
     * @param $tempId
     * @param $url
     * @param $dataArray
     */
    public function sendTempMsg($openId,$tempId,$url,$dataArray){
        $app = new Application(Yii::$app->params['WECHAT']);
        $notice = $app->notice;

        /*
         *
        $openId = 'OPENID';
        $templateId = 'ngqIpbwh8bUfcSsECmogfXcV14J0tQlEpBO27izEYtY';
        $url = '';
        $data = array(
            "first"    => array("恭喜你购买成功！", '#555555'),
            "keynote1" => array("巧克力", "#336699"),
            "keynote2" => array("39.8元", "#FF0000"),
            "keynote3" => array("2014年9月16日", "#888888"),
            "remark"   => array("欢迎再次购买！", "#5599FF"),
        );
        */

        $messageId = $notice->send([
            'touser' => $openId,
            'template_id' => $tempId,
            'url' => $url,
            'data' => $dataArray,
        ]);

        var_dump($messageId);
        /*
        $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        var_dump($result);
        */
    }


    /**
     * 群发消息
     */
    public function broadcast($openIdArray){
        $app = new Application(Yii::$app->params['WECHAT']);
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

}
