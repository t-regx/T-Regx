<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Base;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class CallsCountingBase implements Base
{
    use Fails;

    /** @var int */
    private $calls = 0;
    /** @var RawMatchesOffset */
    private $result;

    public function __construct(RawMatchesOffset $result)
    {
        $this->result = $result;
    }

    public function match(): RawMatch
    {
        throw $this->fail();
    }

    public function matchOffset(): RawMatchOffset
    {
        throw $this->fail();
    }

    public function matchAll(): RawMatches
    {
        throw $this->fail();
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        $this->calls++;
        return $this->result;
    }

    public function calls(): int
    {
        return $this->calls;
    }
}
