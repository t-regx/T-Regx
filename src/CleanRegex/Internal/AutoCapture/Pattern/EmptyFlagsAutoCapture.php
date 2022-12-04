<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Pattern;

use TRegx\CleanRegex\Internal\Flags;

class EmptyFlagsAutoCapture implements PatternAutoCapture
{
    public function patternOptionSetting(Flags $flags): string
    {
        return '';
    }

    public function patternModifiers(Flags $flags): string
    {
        return '';
    }
}
