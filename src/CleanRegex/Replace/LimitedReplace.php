<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\Callback\CallbackInvoker;
use TRegx\CleanRegex\Internal\Replace\Counter;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\StateStrategy;
use TRegx\CleanRegex\Internal\Replace\GroupReplace\GroupReplace;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class LimitedReplace
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var StateStrategy */
    private $countingStrategy;
    /** @var CallbackInvoker */
    private $invoker;
    /** @var GroupReplace */
    private $groupReplace;

    public function __construct(Definition $definition, Subject $subject, int $pregLimit, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $pregLimit;
        $this->countingStrategy = new StateStrategy($countingStrategy, new Counter($definition, $subject, $pregLimit));
        $this->invoker = new CallbackInvoker($definition, $subject, $pregLimit, $this->countingStrategy);
        $this->groupReplace = new GroupReplace($definition, $subject, $pregLimit, $this->countingStrategy);
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::escaped($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $result = preg::replace($this->definition->pattern, $replacement, $this->subject, $this->limit, $replaced);
        $this->countingStrategy->applyReplaced($replaced);
        return $result;
    }

    public function withGroup($nameOrIndex): string
    {
        return $this->groupReplace->withGroup(GroupKey::of($nameOrIndex));
    }

    public function callback(callable $callback): string
    {
        return $this->invoker->invoke($callback);
    }

    public function count(): int
    {
        return $this->countingStrategy->count();
    }
}
