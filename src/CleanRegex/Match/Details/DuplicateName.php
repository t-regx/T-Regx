<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\DuplicateNamedGroupAdapter;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\RuntimeNamedGroup;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\DuplicateNamedGroup;

/**
 * There are two strategies to handling duplicate names in
 * groups (and to handling any group, actually, though in
 * non-duplicate names their results are identical).
 *
 * When there are duplicate group names, used in a pattern,
 * only the last encountered group will be present in the
 * resulting array, because PHP only keeps unique keys
 * in an array. So even if two groups (duplicate) matched
 * two separate strings, only one will be present. Thus,
 * it's impossible to distinguish which exact (indexed)
 * group actually matched the name group. It's possible
 * to sometimes "guess" that certain groups definitely
 * didn't match it (if their values and/or offsets are
 * different), but if they're equal (and it's possible that
 * they're equal), then the distinguishment is impossible.
 *
 * So there are two strategies, to read a group value.
 * They have their advantages and flaws.
 *
 * - Read a group as it was at a compiled pattern
 * - Read a group directly from the runtime result
 *
 * If you read a group at a compile time, it becomes
 * possible to uniquely assign an index to a group,
 * by assuming that the first group in the pattern
 * because "the group", and other groups named with
 * the same names are treated as though they aren't
 * named at all. Basically, removing duplicate names
 * from the pattern, but the first, if there are any.
 *
 * If you read a group at runtime, it becomes slightly
 * more useful, since "in some cases" you can read the
 * actual group value (not all cases), but you can no
 * longer assign an index to the group (you can't tell
 * which group exactly matched it), you can only tell
 * what value does the named group has, but not which
 * group matched it.
 *
 * Examples:
 *
 * Compile strategy:
 * Pattern "(?<g>one)?(?<g>two)?(?<g>three)?" is treated
 * as "(?<g>one)(two)(three)". We just assume the
 * first indexed group is named, other two aren't.
 *
 * Runtime strategy:
 * With pattern "((?<g>one)|(?<g>two))" depending on the
 * subject, only the first or the second group will be matched,
 * we'll know that group "g" was matched either "one" or "two",
 * but we can't tell what index did the group have. We can't
 * distinguish them.
 *
 * The real explanation:
 *
 * When we run the pattern, and get the runtime result, what
 * information do we really have:
 * - The order of the named and indexed groups - which allows
 * us to assign the first occurrence of the duplicately named
 * group to the first indexed group (but not necessarily the
 * matched group), which allows us to use the index() method,
 * but the group may not be matched, and other group with the
 * same name might have it matched.
 * - The value of the named group, but no means to tell exactly
 * which group it is, which allows us to read the group, but
 * no way to say which index it has.
 */
class DuplicateName
{
    /** @var UsedForGroup */
    private $forGroup;
    /** @var MatchEntry */
    private $entry;
    /** @var GroupFacade */
    private $groupFacade;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware           $groupAware,
                                UsedForGroup         $forGroup,
                                MatchEntry           $entry,
                                Subject              $subject,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                Signatures           $signatures)
    {
        $this->forGroup = $forGroup;
        $this->entry = $entry;
        $this->groupFacade = new GroupFacade($subject, $factoryStrategy, $allFactory,
            new NotMatched($groupAware, $subject), new RuntimeNamedGroup(), $signatures);
        $this->groupAware = $groupAware;
    }

    public function group(string $groupName): DuplicateNamedGroup
    {
        $group = new GroupName($groupName);
        if (!$this->groupAware->hasGroup($group->nameOrIndex())) {
            throw new NonexistentGroupException($group);
        }
        return new DuplicateNamedGroupAdapter($groupName, $this->groupFacade->createGroup($group, $this->forGroup, $this->entry));
    }

    public function get(string $groupName): string
    {
        return $this->group($groupName)->text();
    }

    public function matched(string $groupName): bool
    {
        return $this->group($groupName)->matched();
    }
}
