<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\Group;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;

interface GroupAutoCapture
{
    public function imposedNonCapture(): bool;

    public function groupOptionSetting(string $options): OptionSetting;
}
