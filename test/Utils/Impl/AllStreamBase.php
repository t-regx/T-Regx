<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\StreamBase;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class AllStreamBase extends StreamBase
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        parent::__construct(new ThrowApiBase());
        $this->matches = $matches;
    }

    public function all(): RawMatchesOffset
    {
        return $this->matches;
    }
}
