<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Definition;
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
        preg::match_all($this->definition->pattern, $this->subject, $matches, \PREG_OFFSET_CAPTURE);
        return new RawMatchesOffset($matches);
    }
}
