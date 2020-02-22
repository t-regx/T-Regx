<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface IRawMatch
{
    public function matched(): bool;

    public function getText(): string;
}
