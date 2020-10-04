<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

interface ByGroupReplacePattern
{
    public function orElseThrow(string $exceptionClassName = GroupNotMatchedException::class): string;

    public function orElseIgnore(): string;

    public function orElseEmpty(): string;

    public function orElseWith(string $replacement): string;

    public function orElseCalling(callable $replacementProducer): string;

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

    /**
     * @param string[] $occurrencesAndReplacements
     * @param callable $mapper
     * @return OptionalStrategySelector
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): OptionalStrategySelector;
}
