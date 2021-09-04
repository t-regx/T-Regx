<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface MatchEntry
{
    public function text(): string;

    public function byteOffset(): int;
}
