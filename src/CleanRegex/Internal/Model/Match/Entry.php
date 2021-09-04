<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface Entry
{
    public function text(): string;

    public function byteOffset(): int;
}
