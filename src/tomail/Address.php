<?php

declare(strict_types=1);

namespace Feiyufri\ToMail\tomail;

class Address
{
    private string $toMail;

    private string $toName;

    public function __construct(string $toMail,string $toName)
    {
        // 验证邮箱格式
        $this->toMail = $toMail;
        $this->toName = $toName;        
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
