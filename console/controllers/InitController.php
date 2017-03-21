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
class InitController extends BaseConsoleController
{

    /**
     * 初始化mysql数据库表
     */
   public function actionMysql(){
       //todo
   }

}
