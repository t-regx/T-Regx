<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface IRawMatches
{
    public function matched(): bool;

    /**
     * @return string[]
     */
    public function getTexts(): array;
}
