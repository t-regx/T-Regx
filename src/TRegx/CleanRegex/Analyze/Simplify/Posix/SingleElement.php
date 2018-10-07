<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Posix;

class SingleElement implements Element
{
    /** @var string */
    private $element;

    public function __construct(string $element)
    {
        $this->element = $element;
    }

    public function get(): string
    {
        return $this->element;
    }

    public function contains(Element $element): bool
    {
        return $this->element === $element;
    }
}
