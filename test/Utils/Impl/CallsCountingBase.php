<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class CallsCountingBase implements Base
{
    /** @var int */
    private $calls = 0;
    /** @var RawMatchesOffset */
    private $result;

    public function __construct(RawMatchesOffset $result)
    {
        $this->result = $result;
    }

    public function getPattern(): Definition
    {
        $this->calls++;
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

    public function getUserData(): UserData
    {
        throw $this->fail();
    }

    public function getSubject(): string
    {
        throw $this->fail();
    }

    public function calls(): int
    {
        return $this->calls;
    }

    private function fail(): AssertionError
    {
        return new AssertionError("Failed to assert that method from Base wasn't called");
    }
}
