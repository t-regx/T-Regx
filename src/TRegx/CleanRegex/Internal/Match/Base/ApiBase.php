<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\EmptyRawMatch;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchGroupable;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchNullable;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\SafeRegex\preg;
use function defined;

class ApiBase implements Base
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var UserData */
    private $userData;

    public function __construct(Pattern $pattern, string $subject, UserData $userData)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->userData = $userData;
    }

    public function getPattern(): Pattern
    {
        return $this->pattern;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function match(): RawMatch
    {
        preg::match($this->pattern->pattern, $this->subject, $match);
        return new RawMatch($match);
    }

    public function matchOffset(): RawMatchOffset
    {
        preg::match($this->pattern->pattern, $this->subject, $match, PREG_OFFSET_CAPTURE);
        return new RawMatchOffset($match);
    }

    public function matchGroupable(): IRawMatchGroupable
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            $count = preg::match($this->pattern->pattern, $this->subject, $match, PREG_UNMATCHED_AS_NULL);
            if ($count === 0) {
                return new EmptyRawMatch();
            }
            return new RawMatchNullable($match);
        }
        $count = preg::match($this->pattern->pattern, $this->subject, $match, PREG_OFFSET_CAPTURE);
        if ($count === 0) {
            return new EmptyRawMatch();
        }
        return new RawMatchOffset($match);
    }

    public function matchAll(): RawMatches
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return new RawMatches($matches);
    }

    public function matchAllOffsets(): IRawMatchesOffset
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, $this->matchAllOffsetsFlags());
        return new RawMatchesOffset($matches);
    }

    private function matchAllOffsetsFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL;
        }
        return PREG_OFFSET_CAPTURE;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }
}
