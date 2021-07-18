<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\SafeRegex\preg;

class ApiBase implements Base
{
    /** @var Definition */
    private $definition;
    /** @var string */
    private $subject;
    /** @var UserData */
    private $userData;

    public function __construct(Definition $definition, string $subject, UserData $userData)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->userData = $userData;
    }

    public function getPattern(): Definition
    {
        return $this->definition;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function match(): RawMatch
    {
        preg::match($this->definition->pattern, $this->subject, $match);
        return new RawMatch($match);
    }

    public function matchOffset(): RawMatchOffset
    {
        preg::match($this->definition->pattern, $this->subject, $match, \PREG_OFFSET_CAPTURE);
        return new RawMatchOffset($match, 0);
    }

    public function matchAll(): RawMatches
    {
        preg::match_all($this->definition->pattern, $this->subject, $matches);
        return new RawMatches($matches);
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        preg::match_all($this->definition->pattern, $this->subject, $matches, $this->matchAllOffsetsFlags());
        return new RawMatchesOffset($matches);
    }

    private function matchAllOffsetsFlags(): int
    {
        if (\defined('PREG_UNMATCHED_AS_NULL')) {
            return \PREG_OFFSET_CAPTURE | \PREG_UNMATCHED_AS_NULL;
        }
        return \PREG_OFFSET_CAPTURE;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public function getUnfilteredBase(): Base
    {
        return $this;
    }
}
