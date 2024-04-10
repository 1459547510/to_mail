<?php

declare(strict_types=1);

namespace Feiyufri\ToMail\tomail;

class Attachment
{
    private string $path;

    private string $rename;

    public function __construct(string $path,string $rename = '')
    {
        // 验证邮箱格式
        $this->path = $path;
        $this->rename = $rename;        
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
