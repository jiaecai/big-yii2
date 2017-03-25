<?php

namespace common\util;

use Yii;
use Flc\Alidayu\Requests\IRequest;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;

/**
 * Class NotificationUtil
 * @package common\util
 */

class NotificationUtil{

    /**
     * 发送短信
     * @param string $recNum
     * @param string $templateCode
     * @param array $params
     * @param string $sign
     * @return false|object
     */
    public static function sendSms($recNum,$templateCode,$params,$sign){
        $client = new Client(new App(Yii::$app->params['TOP']));

        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum($recNum)
            ->setSmsParam($params)
            ->setSmsTemplateCode($templateCode)
            ->setSmsFreeSignName($sign);

        $resp = $client->execute($req);
        //返回结果
        //var_dump($resp);
        //print_r($resp->result->model);

        return $resp;
    }


    /**
     * 发送微信模板消息
     * @param $openId
     * @param $tempId
     * @param $url
     * @param $dataArray
     */
    public static function sendWxTempMsg($openId,$tempId,$dataArray,$url=null){
        return \common\forms\EasyWechatForm::sendTempMsg($openId,$tempId,$dataArray,$url);
    }

}