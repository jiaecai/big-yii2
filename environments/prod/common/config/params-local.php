<?php
return [
    'wxOptions' => [
        'debug'  => false,
        'app_id' => 'your-app-id',
        'secret' => 'you-secret',
        'token'  => 'easywechat',
        // 'aes_key' => null, // 可选
        'log' => [
            'level' => 'debug',
            'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
        ],
        //...
    ]
];
