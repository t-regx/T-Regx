<?php
namespace TRegx\CleanRegex\Internal\Model;

interface RawMatchInterface
{
    public function matched(): bool;

    public function getMatch(): string;
}
