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
use EasyWeChat\Message\Text;

/**
 * Class WechatForm
 * @package common\forms
 */
class WechatForm extends BaseForm
{

    /**
     * 首次关注
     * @param $object
     */
    public static function respSubscribe($openId){

        //todo 自己的流程

        $text = new Text(['content' => "欢迎关注BIG-YII2:".$openId]);
        return $text;
    }

    /**
     * 位置信息的处理
     * @param $object
     */
    public static function respLocation($openId){
        //todo::更新用户位置信息
        /*
        $content[] = array(
            "Title" => "您在".$object->CreateTime."的位置",
            "Description" => "经纬度:lat=".$object->Latitude.",lng=".$object->Longitude,
            "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
            "Url" => "http://www.youqu2015.com/index.html");
        */
        $content=null;
        return $content;
    }


}
