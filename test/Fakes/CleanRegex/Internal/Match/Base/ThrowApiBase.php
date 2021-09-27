<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Base;

use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Definitions;
use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ThrowApiBase extends ApiBase
{
    use Fails;

    public function __construct()
    {
        parent::__construct(Definitions::pcre('//'), new ThrowSubject(), new UserData());
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

    public function matchAllOffsets(): RawMatchesOffset
    {
        throw $this->fail();
    }

    public function getUserData(): UserData
    {
        throw new \Exception();
    }

    public function getSubject(): string
    {
        throw $this->fail();
    }
}
