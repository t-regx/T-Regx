<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\Callback\CallbackInvoker;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\GroupReplace\GroupReplace;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\SafeRegex\preg;

class SpecificReplacePatternImpl implements SpecificReplacePattern
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var CallbackInvoker */
    private $invoker;
    /** @var GroupReplace */
    private $groupReplace;

    public function __construct(Definition $definition, Subject $subject, int $limit, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->countingStrategy = $countingStrategy;
        $this->invoker = new CallbackInvoker($definition, $subject, $limit, $countingStrategy);
        $this->groupReplace = new GroupReplace($definition, $subject, $limit, $this->countingStrategy);
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
}
