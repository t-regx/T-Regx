<?php
namespace Test\Utils\Impl;

use AssertionError;
use Throwable;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ConstantAllBase implements Base
{
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

    public function getPattern(): Definition
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

    public function getUserData(): UserData
    {
        throw $this->fail();
    }

    public function getSubject(): string
    {
        if ($this->subject === null) {
            throw $this->fail();
        }
        return $this->subject;
    }

    private function fail(): Throwable
    {
        return new AssertionError("Failed to assert that Base wasn't used");
    }
}
