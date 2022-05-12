<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\CapturingGroup;

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
        if ($key instanceof CapturingGroup) {
            if ($key->matched()) {
                return $key->text();
            }
            throw GroupNotMatchedException::forGroupBy(GroupKey::of($key->usedIdentifier()));
        }
        if (\is_int($key) || \is_string($key)) {
            return $key;
        }
        throw new InvalidReturnValueException($this->methodName, 'int|string', new ValueType($key));
    }
}
