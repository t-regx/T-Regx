<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Match\FindFirst\Optional;

interface ByGroupReplacePattern extends Optional
{
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string;

    public function orIgnore(): string;

    public function orEmpty(): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return OptionalStrategySelector
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function map(array $occurrencesAndReplacements): OptionalStrategySelector;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return OptionalStrategySelector
     * @throws \InvalidArgumentException
     */
    public function mapIfExists(array $occurrencesAndReplacements): OptionalStrategySelector;

    public function callback(callable $callback): string;
}
