<?php

declare(strict_types=1);

namespace Feiyufri\ToMail\template;

use Feiyufri\ToMail\BaseMailTemplate;

class TextTemplate extends BaseMailTemplate
{
    protected string $title = '徐桑的测试邮件（服务端发送）';

    protected string $body = '<h1>你好%username%</h1><p>这是一封测试邮件，你可以忽略此邮件</p>';

    protected string $altBody = '暂不支持显示改邮件格式';

    protected bool $isHtml = true;

    protected function replaced(array $data)
    {
        preg_match_all('/\%([^\%]+)\%/', $this->body, $matches);
        $newData = [];
        $parameters = [];
        if($matches){
            foreach($matches[1] as $v){
                if(!isset($data[$v])){
                    throw new \Exception('缺少模板参数' . $v);
                }else{
                    $newData[] = $data[$v];
                    $parameters[] = "%{$v}%";
                }
            }
        }

        $this->body = str_replace($parameters, $newData, $this->body);
    }

    public function __get($name)
    {
        return $this->$name;
    }
}

