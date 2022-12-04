<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Group;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\LegacyOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;

class LegacyGroupAutoCapture implements GroupAutoCapture
{
    public function imposedNonCapture(): bool
    {
        return true;
    }

    public function optionSetting(string $options): OptionSetting
    {
        return new LegacyOptionSetting($options);
    }
}
