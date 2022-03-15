<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

/**
 * @deprecated
 */
class ApiBase implements Base
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function match(): RawMatch
    {
        preg::match($this->definition->pattern, $this->subject, $match);
        return new RawMatch($match);
    }

    public function matchOffset(): RawMatchOffset
    {
        preg::match($this->definition->pattern, $this->subject, $match, \PREG_OFFSET_CAPTURE);
        return new RawMatchOffset($match);
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
}
