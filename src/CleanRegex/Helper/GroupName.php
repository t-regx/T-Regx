<?php
namespace TRegx\CleanRegex\Helper;

use TRegx\CleanRegex\Internal\GroupNameValidator;

abstract class GroupName
{
    public static function isValid($nameOrIndex): bool
    {
        return (new GroupNameValidator($nameOrIndex))->isGroupValid();
    }
}
