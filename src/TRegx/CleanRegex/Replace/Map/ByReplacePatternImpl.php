<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

class ByReplacePatternImpl implements ByReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var NonReplacedStrategy */
    private $mapStrategy;

    public function __construct(GroupFallbackReplacer $fallbackReplacer, NonReplacedStrategy $mapStrategy)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->mapStrategy = $mapStrategy;
    }

    public function group($nameOrIndex): ByGroupReplacePattern
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return new ByGroupReplacePatternImpl($this->fallbackReplacer, $nameOrIndex);
    }

    public function map(array $map): string
    {
        return $this->replaceByMap($map, $this->mapStrategy);
    }

    public function mapIfExists(array $map): string
    {
        return $this->replaceByMap($map, new DefaultStrategy());
    }

    public function replaceByMap(array $map, NonReplacedStrategy $missingKeyReplacementStrategy): string
    {
        return $this->fallbackReplacer->replaceOrFallback(
            0,
            new StrategyFallbackAdapter(new DictionaryMapper($map), $missingKeyReplacementStrategy, ''),
            ThrowStrategy::internalException());
    }
}
