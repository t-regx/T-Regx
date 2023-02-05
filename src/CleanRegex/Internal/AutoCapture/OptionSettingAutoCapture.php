<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\Flags;
use function str_replace;

class OptionSettingAutoCapture implements AutoCapture
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
        return str_replace('n', '', $flags->pcreModifiers());
    }

    public function imposedNonCapture(): bool
    {
        return false;
    }

    public function optionSetting(string $options): OptionSetting
    {
        return new IdentityOptionSetting($options);
    }
}
