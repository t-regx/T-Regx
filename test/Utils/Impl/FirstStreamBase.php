<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\StreamBase;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class FirstStreamBase extends StreamBase
{
    /** @var int */
    private $index;
    /** @var RawMatchOffset */
    private $match;

    public function __construct(int $index, RawMatchOffset $match)
    {
        parent::__construct(new ThrowApiBase());
        $this->index = $index;
        $this->match = $match;
    }

    public function first(): RawMatchOffset
    {
        return $this->match;
    }

    public function firstKey(): int
    {
        return $this->index;
    }
}