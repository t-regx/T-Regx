<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\SubjectableEx;
use TRegx\SafeRegex\preg;

class ReplacePatternCallbackInvoker
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Pattern $pattern, string $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function invoke(callable $callback): string
    {
        return preg::replace_callback(
            $this->pattern->pattern,
            $this->getObjectCallback($callback),
            $this->subject,
            $this->limit);
    }

    private function getObjectCallback(callable $callback): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern(), $this->limit);
        return $object->getCallback();
    }

    private function analyzePattern(): IRawMatchesOffset
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        return new RawMatchesOffset($matches, new SubjectableEx());
    }
}
