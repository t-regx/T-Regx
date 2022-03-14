<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Base;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ConstantFirstBase implements Base
{
    use Fails;

    /** @var RawMatchOffset */
    private $matchFirst;

    public function __construct(RawMatchOffset $matchFirst)
    {
        $this->matchFirst = $matchFirst;
    }

    public function match(): RawMatch
    {
        throw $this->fail();
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
