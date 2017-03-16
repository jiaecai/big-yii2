<?php

namespace common\base;

use yii\helpers\Json;
use yii\console\Controller;

class BaseConsoleController extends Controller
{
    public function renderJson($data)
    {
        print(Json::encode($data));
    }
}