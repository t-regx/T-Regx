<?php
namespace TRegx\CleanRegex\Helper;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

abstract class GroupName
{
    public static function isValid($nameOrIndex): bool
    {
        try {
            GroupKey::of($nameOrIndex)->nameOrIndex();
        } catch (InvalidArgumentException $exception) {
            return false;
        }
        return true;
    }
}
