<?php
namespace Test\Fakes\CleanRegex\Internal\Pcre\Legacy;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatches;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

class ConstantFirstBase implements Base
{
    use Fails;

    /** @var RawMatchOffset */
    private $matchFirst;

    public function __construct(RawMatchOffset $matchFirst)
    {
        $this->matchFirst = $matchFirst;
    }

    public function matchOffset(): RawMatchOffset
    {
        return $this->matchFirst;
    }

    public function matchAll(): RawMatches
    {
        throw $this->fail();
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        throw $this->fail();
    }
}
