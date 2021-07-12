<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\SafeRegex\preg;

class ReplacePatternCallbackInvoker
{
    /** @var Definition */
    private $definition;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;
    /** @var SubjectRs */
    private $substitute;
    /** @var CountingStrategy */
    private $countingStrategy;

    public function __construct(Definition $definition, Subjectable $subject, int $limit, SubjectRs $substitute, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->countingStrategy = $countingStrategy;
    }

    public function invoke(callable $callback, ReplaceCallbackArgumentStrategy $strategy): string
    {
        $result = $this->pregReplaceCallback($callback, $replaced, $strategy);
        $this->countingStrategy->count($replaced);
        if ($replaced === 0) {
            return $this->substitute->substitute($this->subject->getSubject()) ?? $result;
        }
        return $result;
    }

    private function pregReplaceCallback(callable $callback, ?int &$replaced, ReplaceCallbackArgumentStrategy $strategy): string
    {
        return preg::replace_callback($this->definition->pattern,
            $this->getObjectCallback($callback, $strategy),
            $this->subject->getSubject(),
            $this->limit,
            $replaced);
    }

    private function getObjectCallback(callable $callback, ReplaceCallbackArgumentStrategy $strategy): callable
    {
        if ($this->limit === 0) {
            return static function () {
            };
        }
        return $this->createObjectCallback($callback, $strategy);
    }

    private function createObjectCallback(callable $callback, ReplaceCallbackArgumentStrategy $strategy): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern(), $this->limit, $strategy);
        return $object->getCallback();
    }

    private function analyzePattern(): RawMatchesOffset
    {
        preg::match_all($this->definition->pattern, $this->subject->getSubject(), $matches, \PREG_OFFSET_CAPTURE);
        return new RawMatchesOffset($matches);
    }
}
