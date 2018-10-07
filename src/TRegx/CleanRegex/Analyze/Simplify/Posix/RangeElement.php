<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Posix;

class RangeElement implements Element
{
    /** @var string */
    private $start;
    /** @var string */
    private $end;

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function get(): string
    {
        return "$this->start-$this->end";
    }

    public function contains(Element $element): bool
    {
        $value = $element->get();
        return $value >= $this->start && $value <= $this->end;
    }
}
