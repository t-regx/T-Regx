<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Replace\NonReplaced\Map\Exception\MissingReplacementKeyException;

interface ByGroupReplacePattern extends Optional
{
    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class);

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
    public function mapOrDefault(array $occurrencesAndReplacements, string $defaultReplacement): string;
}
