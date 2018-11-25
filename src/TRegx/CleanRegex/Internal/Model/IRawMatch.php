<?php
namespace TRegx\CleanRegex\Internal\Model;

interface IRawMatch
{
    public function matched(): bool;

    public function getMatch(): string;
}
