<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Base;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ConstantAllBase implements Base
{
    use Fails;

    /** @var RawMatchesOffset */
    private $matchesOffset;
    /** @var string|null */
    private $subject;

    public function __construct(RawMatchesOffset $matchesOffset, string $subject = null)
    {
        $this->matchesOffset = $matchesOffset;
        $this->subject = $subject;
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return $this->matchesOffset;
    }

    public function definition(): Definition
    {
        throw $this->fail();
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
