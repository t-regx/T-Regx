<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\PatternInterface;
use TRegx\CleanRegex\Template;

interface PcrePatternBuilder
{
    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string|null $flags
     * @return PatternInterface
     */
    public function bind(string $input, array $values, string $flags = null): PatternInterface;

    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string|null $flags
     * @return PatternInterface
     */
    public function inject(string $input, array $values, string $flags = null): PatternInterface;

    /**
     * @param (string|string[])[] $input
     * @param string|null $flags
     * @return PatternInterface
     */
    public function prepare(array $input, string $flags = null): PatternInterface;

    public function mask(string $mask, array $keywords, string $flags = null): PatternInterface;

    public function template(string $pattern, string $flags = null): Template;
}
