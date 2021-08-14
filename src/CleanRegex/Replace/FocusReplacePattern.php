<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\FocusWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\ReferencesReplacer;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\By\ByReplacePatternImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class FocusReplacePattern implements SpecificReplacePattern
{
    /** @var SpecificReplacePattern */
    private $replacePattern;
    /** @var Definition */
    private $definition;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;
    /** @var GroupKey */
    private $group;
    /** @var CountingStrategy */
    private $countingStrategy;

    public function __construct(SpecificReplacePattern $replacePattern,
                                Definition             $definition,
                                Subjectable            $subject,
                                int                    $limit,
                                GroupKey               $group,
                                CountingStrategy       $countingStrategy)
    {
        $this->replacePattern = $replacePattern;
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->group = $group;
        $this->countingStrategy = $countingStrategy;
    }

    public function with(string $replacement): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($replacement) {
            if (!$detail->matched($this->group->nameOrIndex())) {
                throw new FocusGroupNotMatchedException($detail->subject(), $this->group);
            }
            return $detail->group($this->group->nameOrIndex())->substitute($replacement);
        });
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($replacement) {
            if (!$detail->matched($this->group->nameOrIndex())) {
                throw new FocusGroupNotMatchedException($detail->subject(), $this->group);
            }
            $group = $detail->group($this->group->nameOrIndex());
            return $group->substitute(ReferencesReplacer::replace($replacement, \array_merge(
                [$group->text()],
                $detail->groups()->texts())));
        });
    }

    public function callback(callable $callback): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($callback): string {
            if ($detail->matched($this->group->nameOrIndex())) {
                return $detail->group($this->group->nameOrIndex())->substitute($callback($detail));
            }
            throw new FocusGroupNotMatchedException($detail->subject(), $this->group);
        });
    }

    public function by(): ByReplacePattern
    {
        return new ByReplacePatternImpl(
            new GroupFallbackReplacer(
                $this->definition,
                $this->subject,
                $this->limit,
                new DefaultStrategy(),
                $this->countingStrategy,
                new ApiBase($this->definition, $this->subject, new UserData())),
            new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
            new PerformanceEmptyGroupReplace($this->definition, $this->subject, $this->limit),
            new ReplacePatternCallbackInvoker($this->definition, $this->subject, $this->limit, new DefaultStrategy(), $this->countingStrategy),
            $this->subject,
            new FocusWrapper($this->group));
    }
}
