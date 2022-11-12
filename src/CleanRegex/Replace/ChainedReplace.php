<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Predefinitions;
use TRegx\CleanRegex\Internal\Replace\Callback\CallbackInvoker;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Predefinitions */
    private $predefinitions;
    /** @var Subject */
    private $subject;

    public function __construct(Predefinitions $predefinitions, Subject $subject)
    {
        $this->predefinitions = $predefinitions;
        $this->subject = $subject;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::escaped($replacement));
    }

    public function withReferences(string $replacement): string
    {
        return preg::replace($this->definitionsPatterns(), $replacement, $this->subject);
    }

    private function definitionsPatterns(): array
    {
        $patterns = [];
        foreach ($this->predefinitions->definitions() as $definition) {
            $patterns[] = $definition->pattern;
        }
        return $patterns;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject->asString();
        foreach ($this->predefinitions->definitions() as $definition) {
            $subject = $this->replaceNext($definition, $subject, $callback);
        }
        return $subject;
    }

    private function replaceNext(Definition $definition, string $subject, callable $callback): string
    {
        $invoker = new CallbackInvoker($definition, new Subject($subject), -1, new IgnoreCounting());
        return $invoker->invoke($callback);
    }
}
