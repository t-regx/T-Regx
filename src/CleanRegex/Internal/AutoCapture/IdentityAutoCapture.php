<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\Flags;

class IdentityAutoCapture implements AutoCapture
{
    public function patternOptionSetting(Flags $flags): string
    {
        return '';
    }

    public function patternModifiers(Flags $flags): string
    {
        return $flags->pcreModifiers();
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
