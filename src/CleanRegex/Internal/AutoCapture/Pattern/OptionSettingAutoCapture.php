<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Pattern;

use TRegx\CleanRegex\Internal\Flags;

class OptionSettingAutoCapture implements PatternAutoCapture
{
    public function patternOptionSetting(Flags $flags): string
    {
        if ($flags->noAutoCapture()) {
            return '(?n)';
        }
        return '';
    }

    public function patternModifiers(Flags $flags): string
    {
        return \str_replace('n', '', $flags->toPcreModifiers());
    }
}
