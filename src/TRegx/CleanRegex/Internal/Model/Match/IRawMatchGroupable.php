<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

/**
 * Interface representing a match (a result of `preg_match()` or `preg_match_all()` method),
 * that can be used to confidently assert a capturing group existence.
 *
 * With result of:
 *   - `preg_match()` (IRawMatch) it's not possible (because a group can be either missing, or trimmed by preg_match())
 *      That's why `preg_match()` is used only for `match()->first()` without a callback.
 * With results of:
 *   - `preg_match(PREG_UNMATCHED_AS_NULL)` (IRawMatchNullable)
 *   - `preg_match(PREG_OFFSET_CAPTURE)` (IRawMatchOffset)
 *   - `preg_match_all()` (IRawMatches)
 * ...it is possible to assert a capturing group existence - a lack of such group in the results necessarily means
 * that the group is missing.
 */
interface IRawMatchGroupable
{
    public function matched(): bool;

    public function hasGroup($nameOrIndex): bool;

    public function getGroup($nameOrIndex): ?string;

    public function getGroupByteOffset($nameOrIndex): ?int;
}
