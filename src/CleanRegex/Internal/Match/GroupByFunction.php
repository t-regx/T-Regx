<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;

class GroupByFunction
{
    /** @var string */
    private $methodName;
    /** @var callable */
    private $function;

    public function __construct(string $methodName, callable $function)
    {
        $this->methodName = $methodName;
        $this->function = $function;
    }

    public function apply($argument): string
    {
        return $this->groupKey(($this->function)($argument));
    }

    private function groupKey($key): string
    {
        if ($key instanceof Detail) {
            return $key->text();
        }
        if ($key instanceof Group) {
            return $this->matchedGroup($key);
        }
        if (\is_int($key) || \is_string($key)) {
            return $key;
        }
        throw new InvalidReturnValueException($this->methodName, 'int|string', new ValueType($key));
    }

    private function matchedGroup(Group $group): string
    {
        if ($group->matched()) {
            return $group->text();
        }
        throw $this->notMatched(GroupKey::of($group->usedIdentifier()));
    }

    private function notMatched(GroupKey $group): GroupNotMatchedException
    {
        return new GroupNotMatchedException("Expected to group matches by group $group, but the group was not matched");
    }
}
