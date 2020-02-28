<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

interface ByReplacePattern
{
    /**
     * @param string|int $nameOrIndex
     * @return ByGroupReplacePattern
     * @throws \InvalidArgumentException
     */
    public function group($nameOrIndex): ByGroupReplacePattern;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return OptionalStrategySelector
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function map(array $occurrencesAndReplacements): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return OptionalStrategySelector
     * @throws \InvalidArgumentException
     */
    public function mapIfExists(array $occurrencesAndReplacements): string;
}
