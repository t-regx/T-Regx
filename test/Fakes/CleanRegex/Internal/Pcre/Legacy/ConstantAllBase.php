<?php
namespace Test\Fakes\CleanRegex\Internal\Pcre\Legacy;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatch;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatches;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

class ConstantAllBase implements Base
{
    use Fails;

    /** @var RawMatchesOffset */
    private $matchesOffset;

    public function __construct(RawMatchesOffset $matchesOffset)
    {
        $this->matchesOffset = $matchesOffset;
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return $this->matchesOffset;
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
}
