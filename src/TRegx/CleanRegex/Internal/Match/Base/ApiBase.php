<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;

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

    public function getPattern(): Pattern
    {
        return $this->pattern;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function match(): array
    {
        preg::match($this->pattern->pattern, $this->subject, $match);
        return $match;
    }

    public function matchCountOffset(): array
    {
        $count = preg::match($this->pattern->pattern, $this->subject, $match, PREG_OFFSET_CAPTURE);
        return [$match, $count];
    }

    public function matchCountVerified(): array
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            $flags = PREG_UNMATCHED_AS_NULL;
        } else {
            $flags = PREG_OFFSET_CAPTURE;
        }
        $count = preg::match($this->pattern->pattern, $this->subject, $match, $flags);
        return [$match, $count];
    }

    public function matchAll(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches;
    }

    public function matchAllOffsets(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
