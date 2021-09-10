<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Control implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $control;

    public function __construct(string $control)
    {
        $this->control = $control;
    }

    public function pattern(): string
    {
        return "\c$this->control";
    }
}
