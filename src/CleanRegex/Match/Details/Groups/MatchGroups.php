<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

interface MatchGroups
{
    public function texts(): array;

    public function offsets(): array;

    public function byteOffsets(): array;

    public function names(): array;

    public function count(): int;
}
