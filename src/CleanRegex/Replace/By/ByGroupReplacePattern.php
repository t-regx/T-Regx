<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Replace\By\UnmatchedGroupStrategy;
use TRegx\CleanRegex\Replace\GroupReplace;

interface ByGroupReplacePattern extends GroupReplace
{
    /**
     * @param string[] $occurrencesAndReplacements
     * @return UnmatchedGroupStrategy
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function map(array $occurrencesAndReplacements): GroupReplace;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return UnmatchedGroupStrategy
     * @throws \InvalidArgumentException
     */
    public function mapIfExists(array $occurrencesAndReplacements): GroupReplace;

    public function callback(callable $callback): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @param callable $mapper
     * @return UnmatchedGroupStrategy
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): GroupReplace;
}
