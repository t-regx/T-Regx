<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

interface IRawMatches
{
    public function matched(): bool;

    /**
     * @return string[]
     */
    public function getTexts(): array;
}
