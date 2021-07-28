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
use TRegx\CleanRegex\Internal\Subject;
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
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;
    /** @var GroupKey */
    private $groupId;
    /** @var CountingStrategy */
    private $countingStrategy;

    public function __construct(SpecificReplacePattern $replacePattern,
                                Definition             $definition,
                                string                 $subject,
                                int                    $limit,
                                GroupKey               $groupId,
                                CountingStrategy       $countingStrategy)
    {
        $this->replacePattern = $replacePattern;
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->groupId = $groupId;
        $this->countingStrategy = $countingStrategy;
    }

    public function with(string $replacement): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($replacement) {
            if (!$detail->matched($this->groupId->nameOrIndex())) {
                throw new FocusGroupNotMatchedException($detail->subject(), $this->groupId);
            }
            return $detail->group($this->groupId->nameOrIndex())->substitute($replacement);
        });
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($replacement) {
            if (!$detail->matched($this->groupId->nameOrIndex())) {
                throw new FocusGroupNotMatchedException($detail->subject(), $this->groupId);
            }
            $group = $detail->group($this->groupId->nameOrIndex());
            return $group->substitute(ReferencesReplacer::replace($replacement, \array_merge(
                [$group->text()],
                $detail->groups()->texts())));
        });
    }

    public function callback(callable $callback): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($callback): string {
            if ($detail->matched($this->groupId->nameOrIndex())) {
                return $detail->group($this->groupId->nameOrIndex())->substitute($callback($detail));
            }
            throw new FocusGroupNotMatchedException($detail->subject(), $this->groupId);
        });
    }

    public function by(): ByReplacePattern
    {
        return new ByReplacePatternImpl(
            new GroupFallbackReplacer(
                $this->definition,
                new Subject($this->subject),
                $this->limit,
                new DefaultStrategy(),
                $this->countingStrategy,
                new ApiBase($this->definition, $this->subject, new UserData())),
            new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
            new PerformanceEmptyGroupReplace($this->definition, new Subject($this->subject), $this->limit),
            new ReplacePatternCallbackInvoker($this->definition, new Subject($this->subject), $this->limit, new DefaultStrategy(), $this->countingStrategy),
            $this->subject,
            new FocusWrapper($this->groupId));
    }
}
