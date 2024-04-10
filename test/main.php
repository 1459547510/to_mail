<?php

use Feiyufri\ToMail\template\EatTemplate;
use Feiyufri\ToMail\template\TextTemplate;
use Feiyufri\ToMail\ToMail;
use Feiyufri\ToMail\tomail\Address;

require_once "../vendor/autoload.php";

// 此处的config可以写在框架的config里直接获取
$config = [
    'from_mail' => '***@qq.com',
    'from_name' => '***.top',
    'smtp' => [
        'Host' => 'smtp.***.com',                // SMTP服务器
        'Username' => '***@qq.com',      // SMTP 用户名  即邮箱的用户名
        'Password' => '***',       // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        'SMTPSecure' => 'ssl',                  // 允许 TLS 或者ssl协议
        'Port' => 465,                          // 服务器端口 25 或者465 具体要看邮箱服务器支持
        'SMTPAuth' => true,                     // 允许 SMTP 认证
        'SMTPDebug' => 0,                       // 调试模式输出
        'CharSet' => "UTF-8"                    //设定邮件编码
    ]
];

$toMail = new ToMail($config);
$te = new TextTemplate(['username'=>'feiyu']);
$to = new Address('***@qq.com', 'feiyu');
$res = $toMail->setTemplate($te)->setToAddress($to)->send('xu');
if ($res) {
    echo  '发送成功';
} else {
    return '发送失败' . $toMail->getErrorInfo();
}
