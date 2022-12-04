<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Group;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;

class IdentityGroupAutoCapture implements GroupAutoCapture
{
    public function imposedNonCapture(): bool
    {
        return false;
    }

    public function optionSetting(string $options): OptionSetting
    {
        return new IdentityOptionSetting($options);
    }
}
