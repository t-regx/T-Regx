<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface MatchEntry
{
    public function getText(): string;

    public function byteOffset(): int;
}
