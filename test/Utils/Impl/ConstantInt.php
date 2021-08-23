<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Match\Details\Intable;

class ConstantInt implements Intable
{
    /** @var int */
    private $integer;

    public function __construct(int $integer)
    {
        $this->integer = $integer;
    }

    public function toInt(int $base = null): int
    {
        return $this->integer;
    }
}
