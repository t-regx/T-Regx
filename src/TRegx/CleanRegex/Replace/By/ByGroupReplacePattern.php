<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

interface ByGroupReplacePattern
{
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string;

    public function orIgnore(): string;

    public function orEmpty(): string;

    public function orReturn(string $replacement);

    public function orElse(callable $replacementProducer);

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
