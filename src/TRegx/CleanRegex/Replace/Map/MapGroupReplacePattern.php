<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Replace\Map\Exception\MissingReplacementKeyException;

interface MapGroupReplacePattern
{
    /**
     * @param string[] $occurrencesAndReplacements
     * @return string
     * @throws \InvalidArgumentException
     * @throws MissingReplacementKeyException
     */
    public function map(array $occurrencesAndReplacements): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @return string
     * @throws \InvalidArgumentException
     */
    public function mapIfExists(array $occurrencesAndReplacements): string;

    /**
     * @param string[] $occurrencesAndReplacements
     * @param string $defaultReplacement
     * @return string
     * @throws \InvalidArgumentException
     */
    public function mapDefault(array $occurrencesAndReplacements, string $defaultReplacement): string;
}
