<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;

class FirstKeyStreamBase extends StreamBase
{
    /** @var int */
    private $index;

    public function __construct(int $index)
    {
        parent::__construct(new ThrowApiBase());
        $this->index = $index;
    }

    public function firstKey(): int
    {
        return $this->index;
    }
}
