<?php

declare(strict_types=1);

namespace Feiyufri\ToMail;

abstract class BaseMailTemplate
{
    protected string $title;

    protected string $body;

    protected string $altBody;

    protected bool $isHtml;

    // 附件对象
    protected string $addAttachment;

    public function __construct(array $data)
    {
        try {
            // 替换模板信息
            $this->replaced($data);
        } catch (\Exception $th) {
            throw new \Exception($th->getMessage());
        }
    }

    /**
     * 替换模板数据
     */
    abstract protected function replaced(array $data);

    public function __get($name)
    {
        return $this->$name;
    }
}
