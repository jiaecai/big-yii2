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
class WxController extends BaseConsoleController
{

    /**
     * 设置微信菜单
     */
   public function actionSetMenu(){
       $app = new Application(Yii::$app->params['WECHAT']);
       $menu = $app->menu;

       $ret=$menu->add(Yii::$app->params['WX_MENU']);
       var_dump($ret);

       //查询菜单
       $menus = $menu->all();
        var_dump($menus['menu']['button']);

       //获取自定义菜单
       //$menus = $menu->current();
       var_dump($menus['selfmenu_info']);

       //个性化菜单
       //$menu->add(Yii::$app->params['WX_MENU'], Yii::$app->params['WX_MENU_MATCH']);
       //$menus = $menu->test($openId);  //测试个性化菜单
   }



}
