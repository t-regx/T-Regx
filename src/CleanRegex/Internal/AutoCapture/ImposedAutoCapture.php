<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\LegacyOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\Flags;

class ImposedAutoCapture implements AutoCapture
{
    public function patternOptionSetting(Flags $flags): string
    {
        return '';
    }

    public function patternModifiers(Flags $flags): string
    {
        return \str_replace('n', '', $flags->pcreModifiers());
    }

    public function imposedNonCapture(): bool
    {
        return true;
    }

    public function optionSetting(string $options): OptionSetting
    {
        return new LegacyOptionSetting($options);
    }
}
