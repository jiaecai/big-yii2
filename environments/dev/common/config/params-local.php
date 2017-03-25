<?php
return [
    'WECHAT' => [
        /**
         * Debug 模式，bool 值：true/false
         *
         * 当值为 false 时，所有的日志都不会记录
         */
        'debug'  => true,
        /**
         * 账号基本信息，请从微信公众平台/开放平台获取
         */
        //测试
        'app_id'  => 'wx989a13a72b26347b',         // AppID
        'secret'  => '753cb7b56f319f0254275f36e38b2544',     // AppSecret

        //生产
        //'app_id'=>'wx19b94754bbc80931',
        //'secret'=>'9dfecea058733c3cc958015c791ac1e7',

        'token'   => 'youqu2016',          // Token
        //'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
        /**
         * 日志配置
         *
         * level: 日志级别, 可选为：
         *         debug/info/notice/warning/error/critical/alert/emergency
         * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => '/tmp/easywechat.log',
        ],
        /**
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址
         */
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/big-yii2/frontend/web/index.php?r=wx/oauth-callback',
        ],
        /**
         * 微信支付
         */
        'payment' => [
            'merchant_id'        => '1321478301',
            'key'                => 'youqusaishi20171youqusaishi20171',
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
            // 'device_info'     => '013467007045764',
            // 'sub_app_id'      => '',
            // 'sub_merchant_id' => '',
            // ...
        ],
        /**
         * Guzzle 全局设置
         *
         * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
         */
        'guzzle' => [
            'timeout' => 3.0, // 超时时间（秒）
            //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
        ],
    ],

    'WX_MENU' => array(
        array(
            "type" => "click",
            "name" => "我们在测试",
            "key"  => "click"
        ),
        array(
            "name"       => "精彩马上回",
            "sub_button" => [
                [
                    "type" => "view",
                    "name" => "view",
                    "url"  => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "oauth",
                    "url"  => 'http://www.youqu2015.com/big-yii2/frontend/web/index.php?r=wx/page-need-oauth'
                ],
                [
                    "type" => "view",
                    "name" => "mall",
                    "url"  => 'http://www.youqu2015.com/big-yii2/frontend/web/index.php?r=wx-pay/mall-index'
                ],
                [
                    "type" => "scancode_waitmsg",
                    "name" => "scan",
                    "key" => "scan_qr"
                ],
            ],
        ),
    ),

    //个性化菜单
    'WX_MENU_MATCH' => [
        "tag_id"=>"2",
        "sex"=>"1",
        "country"=>"中国",
        "province"=>"广东",
        "city"=>"广州",
        "client_platform_type"=>"2",
        "language"=>"zh_CN"
    ]
];
