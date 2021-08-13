<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\IdentityWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\By\ByReplacePatternImpl;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\SafeRegex\preg;

class SpecificReplacePatternImpl implements SpecificReplacePattern, CompositeReplacePattern, Subjectable
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

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $result = preg::replace($this->definition->pattern, $replacement, $this->subject->getSubject(), $this->limit, $replaced);
        $this->countingStrategy->count($replaced);
        if ($replaced === 0) {
            return $this->substitute->substitute($this->subject) ?? $result;
        }
        return $result;
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker()->invoke($callback, new MatchStrategy());
    }

    public function by(): ByReplacePattern
    {
        return new ByReplacePatternImpl(
            new GroupFallbackReplacer(
                $this->definition,
                $this,
                $this->limit,
                $this->substitute,
                $this->countingStrategy,
                new ApiBase($this->definition, $this->subject, new UserData())),
            new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
            new PerformanceEmptyGroupReplace($this->definition, $this, $this->limit),
            $this->replaceCallbackInvoker(),
            $this->subject,
            new IdentityWrapper());
    }

    public function focus($nameOrIndex): FocusReplacePattern
    {
        return new FocusReplacePattern($this, $this->definition, $this->subject, $this->limit, GroupKey::of($nameOrIndex), $this->countingStrategy);
    }

    private function replaceCallbackInvoker(): ReplacePatternCallbackInvoker
    {
        return new ReplacePatternCallbackInvoker($this->definition, $this, $this->limit, $this->substitute, $this->countingStrategy);
    }

    public function getSubject(): string
    {
        return $this->subject->getSubject();
    }
}
