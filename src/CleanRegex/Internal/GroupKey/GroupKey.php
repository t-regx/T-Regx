<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Type\ValueType;

abstract class GroupKey
{
    public static function of($group): GroupKey
    {
        if (\is_int($group)) {
            return new GroupIndex($group);
        }
        if (\is_string($group)) {
            return new GroupName($group);
        }
        throw InvalidArgument::typeGiven('Group index must be an integer or a string', new ValueType($group));
    }

    /**
     * @return string|int
     */
    public abstract function nameOrIndex();

    public abstract function __toString(): string;
}
