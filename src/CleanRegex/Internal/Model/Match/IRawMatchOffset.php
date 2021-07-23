<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Model\GroupAware;

interface IRawMatchOffset extends GroupAware, MatchEntry
{
    public function getText(): string;

    public function byteOffset(): int;

    public function isGroupMatched($nameOrIndex): bool;

    public function getGroupTextAndOffset($nameOrIndex): array;

    public function getGroupsTexts(): array;

    public function getGroupsOffsets(): array;
}
