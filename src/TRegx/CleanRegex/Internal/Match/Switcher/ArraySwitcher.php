<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;

class ArraySwitcher implements Switcher
{
    /** @var array */
    private $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function first()
    {
        if (\count($this->elements) === 0) {
            throw new NoFirstSwitcherException();
        }
        return \reset($this->elements);
    }
}
