<?php
namespace Test\Fakes\CleanRegex\Internal\NoAutoCapture;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\Flags;

class ThrowGroupAutoCapture implements AutoCapture
{
    use Fails;

    public function patternOptionSetting(Flags $flags): string
    {
        throw $this->fail();
    }

    public function patternModifiers(Flags $flags): string
    {
        throw $this->fail();
    }

    public function imposedNonCapture(): bool
    {
        throw $this->fail();
    }

    public function optionSetting(string $options): OptionSetting
    {
        throw $this->fail();
    }
}
