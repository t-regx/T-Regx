<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
use TRegx\CleanRegex\Internal\Flags;

class CompositeAutoCapture implements AutoCapture
{
    /** @var PatternAutoCapture */
    private $patternAutoCapture;
    /** @var GroupAutoCapture */
    private $groupAutoCapture;

    public function __construct(PatternAutoCapture $patternAutoCapture, GroupAutoCapture $groupAutoCapture)
    {
        $this->patternAutoCapture = $patternAutoCapture;
        $this->groupAutoCapture = $groupAutoCapture;
    }

    public function patternOptionSetting(Flags $flags): string
    {
        return $this->patternAutoCapture->patternOptionSetting($flags);
    }

    public function patternModifiers(Flags $flags): string
    {
        return $this->patternAutoCapture->patternModifiers($flags);
    }

    public function imposedNonCapture(): bool
    {
        return $this->groupAutoCapture->imposedNonCapture();
    }

    public function optionSetting(string $options): OptionSetting
    {
        return $this->groupAutoCapture->optionSetting($options);
    }
}
