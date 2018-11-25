<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Model\EmptyRawMatch;
use TRegx\CleanRegex\Internal\Model\RawMatch;
use TRegx\CleanRegex\Internal\Model\RawMatches;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\IRawMatchGroupable;
use TRegx\CleanRegex\Internal\Model\RawMatchNullable;
use TRegx\CleanRegex\Internal\Model\RawMatchOffset;
use TRegx\SafeRegex\preg;
use function defined;

class ApiBase implements Base
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function getApiBase(): ApiBase
    {
        return $this;
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

    public function matchAllOffsets(): RawMatchesOffset
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, $this->matchAllOffsetsFlags());
        return new RawMatchesOffset($matches, $this);
    }

    private function matchAllOffsetsFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL;
        }
        return PREG_OFFSET_CAPTURE;
    }
}
