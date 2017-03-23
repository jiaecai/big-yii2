<?php
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

// 配置信息
$config = [
    'app_key'    => '*****',
    'app_secret' => '************',
    // 'sandbox'    => true,  // 是否为沙箱环境，默认false
];


// 使用方法一
$client = new Client(new App($config));
$req    = new AlibabaAliqinFcSmsNumSend;

$req->setRecNum('13312311231')
    ->setSmsParam([
        'number' => rand(100000, 999999)
    ])
    ->setSmsFreeSignName('叶子坑')
    ->setSmsTemplateCode('SMS_15105357');

$resp = $client->execute($req);

// 使用方法二
Client::configure($config);  // 全局定义配置（定义一次即可，无需重复定义）

$resp = Client::request('alibaba.aliqin.fc.sms.num.send', function (IRequest $req) {
    $req->setRecNum('13312311231')
        ->setSmsParam([
            'number' => rand(100000, 999999)
        ])
        ->setSmsFreeSignName('叶子坑')
        ->setSmsTemplateCode('SMS_15105357');
});

// 返回结果
print_r($resp);
print_r($resp->result->model);
?>