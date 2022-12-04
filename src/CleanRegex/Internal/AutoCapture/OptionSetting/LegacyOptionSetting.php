<?php
namespace TRegx\CleanRegex\Internal\AutoCapture\OptionSetting;

class LegacyOptionSetting implements OptionSetting
{
    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $flags;
    }

    public function __toString(): string
    {
        return \str_replace('n', '', $this->flags);
    }
}
