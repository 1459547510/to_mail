# to_mail

对PHPMailer二次封装的发送邮件库（自定义邮件模板），配合框架使用更方便

## 简单使用示例

```php
<?php

use Feiyufri\ToMail\template\EatTemplate;
use Feiyufri\ToMail\template\TextTemplate;
use Feiyufri\ToMail\ToMail;
use Feiyufri\ToMail\tomail\Address;
use Feiyufri\ToMail\tomail\Attachment;

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
$to1 = new Address('***@qq.com', '**');
$to2 = new Address('***@qq.com', '**');
$at = new Attachment('./def.jpg','avatar.png');
// 设置模板
$toMail->setTemplate($te);
// 设置纯文本内容
// $toMail->setContent('标题','内容');
// 设置发送地址
$toMail->setToAddress($to1);
$toMail->setToAddress($to2);
// 设置发送附件
$toMail->setAttachment($at);
$res = $toMail->send();
if ($res) {
    echo  '发送成功';
} else {
    return '发送失败' . $toMail->getErrorInfo();
}


```

## 自定义邮件模板

继承BaseMailTemplate类并实现方法即可

其中TextTemplate是一个测试模板可以参考使用

可以将内容设置为一个url，在replaced方法中获取url源码并发送（注意邮件无法加载外链css文件）

## 配合thinkphp使用

可以在config中新键配置文件如mail.php,将以下配置信息复制进去并修改您的配置文件

```php
<?php

return [
    'from_mail' => '***@qq.com', 
    'from_name' => '***', 
    'smtp' => [
        'Host' => 'smtp.qq.com',                // SMTP服务器
        'Username' => '***@qq.com',      // SMTP 用户名  即邮箱的用户名
        'Password' => '***',       // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        'SMTPSecure' => 'ssl',                  // 允许 TLS 或者ssl协议
        'Port' => 465,                          // 服务器端口 25 或者465 具体要看邮箱服务器支持
        'SMTPAuth' => true,                     // 允许 SMTP 认证
        'SMTPDebug' => 0,                       // 调试模式输出
        'CharSet' => "UTF-8"                    //设定邮件编码
    ]
];
```

支持您添加多个邮箱信息，例如

```php
<?php
[
  'smtp2'=>[
    'Host' => 'smtp.qq.com',                // SMTP服务器
    'Username' => '***@qq.com',      // SMTP 用户名  即邮箱的用户名
    'Password' => '***',       // SMTP 密码  部分邮箱是授权码(例如163邮箱)
    'SMTPSecure' => 'ssl',                  // 允许 TLS 或者ssl协议
    'Port' => 465,                          // 服务器端口 25 或者465 具体要看邮箱服务器支持
    'SMTPAuth' => true,                     // 允许 SMTP 认证
    'SMTPDebug' => 0,                       // 调试模式输出
    'CharSet' => "UTF-8"                    //设定邮件编码
  ]
]
```

可以在实例化ToMail时传入第二个参数

```
new ToMail(config('mail'),smtp2);
```

<br/>

***

感谢您的使用