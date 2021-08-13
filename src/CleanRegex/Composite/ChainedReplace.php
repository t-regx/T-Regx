<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\ReplaceReferences;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Definition[] */
    private $definitions;
    /** @var Subjectable */
    private $subject;
    /** @var SubjectRs */
    private $substitute;

    public function __construct(array $definitions, Subjectable $subject, SubjectRs $substitute)
    {
        $this->definitions = $definitions;
        $this->subject = $subject;
        $this->substitute = $substitute;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $subject = $this->subject->getSubject();
        foreach ($this->definitions as $definition) {
            $subject = preg::replace($definition->pattern, $replacement, $subject);
        }
        return $subject;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject->getSubject();
        foreach ($this->definitions as $definition) {
            $subject = $this->replaceNext($definition, $subject, $callback);
        }
        return $subject;
    }

    private function replaceNext(Definition $definition, string $subject, callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker($definition, new Subject($subject), -1, $this->substitute, new IgnoreCounting());
        return $invoker->invoke($callback, new MatchStrategy());
    }
}
