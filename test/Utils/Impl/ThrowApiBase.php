<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class ThrowApiBase extends ApiBase
{
    public function __construct()
    {
        parent::__construct(InternalPattern::pcre('//'), '', new UserData());
    }

    public function getPattern(): InternalPattern
    {
        throw new \Exception();
    }

    public function match(): RawMatch
    {
        throw new \Exception();
    }

    public function matchOffset(): RawMatchOffset
    {
        throw new \Exception();
    }

    public function matchAll(): RawMatches
    {
        throw new \Exception();
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        throw new \Exception();
    }

    public function getUserData(): UserData
    {
        throw new \Exception();
    }

    public function getSubject(): string
    {
        throw new \Exception();
    }
}
