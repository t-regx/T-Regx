<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Pattern;

use TRegx\CleanRegex\Internal\Flags;

interface PatternAutoCapture
{
    public function patternOptionSetting(Flags $flags): string;

    public function patternModifiers(Flags $flags): string;
}
