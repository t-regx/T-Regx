<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;

/**
 * This class can be safely used only with indexed groups,
 * or with named groups in a pattern that doesn't allow
 * duplicate groups (no 'J' modifier used).
 *
 * "Runtime" here means that the resulting group <b>value</b>
 * is determined based on the given subject (runtime), not
 * based a pattern (compile).
 * Because of PHP specifics, a duplicate named group can't
 * be uniquely assigned a group index (we can't determine
 * which named group has which index).
 *
 * That's why, the resulting group must be presented in
 * such a way, that its index can't be used! Because the
 * index will be unpredictable, and not assigned to the
 * name.
 */
class RuntimeGroupFacade extends GroupFacade
{
    public function __construct(IRawMatchOffset $match, Subjectable $subject, string $group, GroupFactoryStrategy $factoryStrategy, MatchAllFactory $allFactory)
    {
        parent::__construct($match, $subject, $group, $factoryStrategy, $allFactory);
    }

    protected function directIdentifier()
    {
        return $this->usedIdentifier; // when usedIdentifier is used, then parsed (runtime) group is used
    }
}
