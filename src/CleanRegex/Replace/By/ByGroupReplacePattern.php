<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Replace\GroupReplace;

interface ByGroupReplacePattern extends GroupReplace
{
    /**
     * @param string[] $occurrencesAndReplacements
     * @return GroupReplace
     */
    public function map(array $occurrencesAndReplacements): GroupReplace;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return GroupReplace
     */
    public function mapIfExists(array $occurrencesAndReplacements): GroupReplace;

    public function callback(callable $callback): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @param callable $mapper
     * @return GroupReplace
     */
    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): GroupReplace;
}
