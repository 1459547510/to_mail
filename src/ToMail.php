<?php

declare(strict_types=1);

namespace Feiyufri\ToMail;

use Feiyufri\ToMail\tomail\Address;
use Feiyufri\ToMail\tomail\Attachment;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class ToMail
{
    private PHPMailer $mail;
    private string $fromMail;
    private string $fromName;

    private string $ErrorInfo;

    // 地址对象
    private array $address = [];

    /**
     * @param array $config = [
     * 'from_mail' => '***@qq.com', 
     * 'from_name' => '***', 
     * 'smtp' => [
     *    'Host' => 'smtp.***.com',                 // SMTP服务器
     *    'Username' => '***@qq.com',               // SMTP 用户名  即邮箱的用户名
     *    'Password' => '***',                      // SMTP 密码  部分邮箱是授权码(例如163邮箱)
     *    'SMTPSecure' => 'ssl',                    // 允许 TLS 或者ssl协议
     *    'Port' => 465,                            // 服务器端口 25 或者465 具体要看邮箱服务器支持
     *    'SMTPAuth' => true,                       // 允许 SMTP 认证
     *    'SMTPDebug' => 0,                         // 调试模式输出
     *    'CharSet' => "UTF-8"                      //设定邮件编码
     *  ]
     * ]
     */
    public function __construct(array $config, string $configName = 'smtp')
    {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->fromName = $config['from_name'];
        $this->fromMail = $config['from_mail'];
        $config = $config[$configName];
        foreach ($config as $k => $v) {
            $this->mail->$k = $v;
        }
    }

    /**
     * 启用html格式发送
     */
    public function setHtml(): ToMail
    {
        $this->mail->isHTML(true);
        return $this;
    }

    /**
     * 设置内容
     * @param string $title 邮件标题
     * @param string $body  邮件内容
     * @param string $altBody  邮件替代体
     * @return ToMail
     */
    public function setContent(string $title, string $body, string $altBody = '暂不支持显示此内容'): ToMail
    {
        $this->mail->Subject = $title;
        $this->mail->Body    = $body;
        $this->mail->AltBody = $altBody;
        return $this;
    }

    /**
     * 设置收件人
     * @param Address $address 收件人object
     * @return ToMail
     */
    public function setToAddress(Address $address): ToMail
    {
        $this->mail->addAddress($address->toMail, $address->toName);  // 收件人
        $this->address[] = $address;

        return $this;
    }

    /**
     * 设置附件
     * @param Attachment $attachment 附件object
     * @return ToMail
     */
    public function setAttachment(Attachment $attachment): ToMail
    {
        if ($attachment->rename) {
            $this->mail->addAttachment($attachment->path, $attachment->rename);
        } else {
            $this->mail->addAttachment($attachment->path);
        }

        return $this;
    }

    /**
     * 发送邮件
     * @param array||callable $sucLogBackFun 错误回调函数
     * @param array||callable $errLogBackFun 错误回调函数
     * @return bool
     */
    public function send($sucLogBackFun = null, $errLogBackFun = null): bool
    {
        try {
            $this->mail->setFrom($this->fromMail, $this->fromName);
            if (count($this->address) == 0) {
                throw new \Exception("未设置收件人");
            }
            $this->mail->addReplyTo($this->fromMail, $this->fromName);  // 回复邮箱

            $this->mail->send();
            if ($sucLogBackFun) {
                @call_user_func($errLogBackFun);
            }
        } catch (\Exception $th) {
            $this->ErrorInfo = $th->getMessage();
            if ($errLogBackFun) {
                // array || callable     拒绝报错  这只是一个报错时的回调函数
                @call_user_func($errLogBackFun);
            }
            return false;
        }
        return true;
    }

    /**
     * 使用模板
     */
    public function setTemplate(BaseMailTemplate $template)
    {
        if ($template->isHtml) {
            $this->setHtml();
        }
        $this->mail->Subject = $template->title;
        $this->mail->Body    = $template->body;
        $this->mail->AltBody = $template->altBody;

        return $this;
    }

    /**
     * 获取错误信息
     */
    public function getErrorInfo()
    {
        return $this->ErrorInfo;
    }
}
