<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Control implements Entity
{
    use TransitiveFlags, QuotesRaw;

    /** @var string */
    private $control;

    public function __construct(string $control)
    {
        $this->control = $control;
    }

    public function raw(): string
    {
        return "\c$this->control";
    }
}
