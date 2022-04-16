<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\WholeMatch;
use TRegx\CleanRegex\Internal\Replace\BrokenLspGroupAware;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\NaiveSubstitute;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Definition[] */
    private $definitions;
    /** @var Subject */
    private $subject;
    /** @var SubjectRs */
    private $substitute;

    public function __construct(array $definitions, Subject $subject, SubjectRs $substitute)
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
        return preg::replace($this->definitionsPatterns(), $replacement, $this->subject);
    }

    private function definitionsPatterns(): array
    {
        $patterns = [];
        foreach ($this->definitions as $definition) {
            $patterns[] = $definition->pattern;
        }
        return $patterns;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject->asString();
        foreach ($this->definitions as $definition) {
            $subject = $this->replaceNext($definition, $subject, $callback);
        }
        return $subject;
    }

    private function replaceNext(Definition $definition, string $subjectString, callable $callback): string
    {
        $subject = new Subject($subjectString);
        $invoker = new ReplacePatternCallbackInvoker($definition, $subject, -1, new IgnoreCounting(),
            new BrokenLspGroupAware(), new WholeMatch(), new NaiveSubstitute($subject, $this->substitute));
        return $invoker->invoke($callback, new MatchStrategy());
    }
}
