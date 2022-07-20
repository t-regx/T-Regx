<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\FocusWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\ReferencesReplacer;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;

class FocusReplacePattern implements SpecificReplacePattern
{
    /** @var SpecificReplacePattern */
    private $replacePattern;
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var GroupKey */
    private $group;
    /** @var CountingStrategy */
    private $countingStrategy;

    public function __construct(SpecificReplacePattern $replacePattern,
                                Definition             $definition,
                                Subject                $subject,
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
                throw new FocusGroupNotMatchedException($this->group);
            }
            return $detail->group($this->group->nameOrIndex())->substitute($replacement);
        });
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($replacement) {
            if (!$detail->matched($this->group->nameOrIndex())) {
                throw new FocusGroupNotMatchedException($this->group);
            }
            $group = $detail->group($this->group->nameOrIndex());
            return $group->substitute(ReferencesReplacer::replace($replacement, \array_merge(
                [$group->text()],
                $this->groupTexts($detail))));
        });
    }

    public function callback(callable $callback): string
    {
        return $this->replacePattern->callback(function (Detail $detail) use ($callback): string {
            if ($detail->matched($this->group->nameOrIndex())) {
                return $detail->group($this->group->nameOrIndex())->substitute($callback($detail));
            }
            throw new FocusGroupNotMatchedException($this->group);
        });
    }

    /**
     * @deprecated
     */
    public function by(): ByReplacePattern
    {
        return new ByReplacePattern(
            new GroupFallbackReplacer(
                $this->definition,
                $this->subject,
                $this->limit,
                new DefaultStrategy(),
                $this->countingStrategy,
                new ApiBase($this->definition, $this->subject)),
            new LazyMessageThrowStrategy(),
            new PerformanceEmptyGroupReplace($this->definition, $this->subject, $this->limit),
            $this->definition, $this->limit, $this->countingStrategy,
            new LightweightGroupAware($this->definition),
            $this->subject,
            new FocusWrapper($this->group));
    }

    private function groupTexts(Detail $detail): array
    {
        $texts = [];
        foreach ($detail->groups() as $group) {
            $texts[] = $group->text();
        }
        return $texts;
    }
}
