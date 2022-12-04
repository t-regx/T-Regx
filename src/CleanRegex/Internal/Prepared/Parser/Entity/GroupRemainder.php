<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\OptionSetting;
use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupRemainder implements Entity
{
    use PatternEntity;

    /** @var string */
    private $flags;
    /** @var OptionSetting */
    private $setting;

    public function __construct(string $flags, OptionSetting $setting)
    {
        $this->flags = $flags;
        $this->setting = $setting;
    }

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->appendFlags($this->flags);
    }

    public function pattern(): string
    {
        return "(?$this->setting)";
    }
}
