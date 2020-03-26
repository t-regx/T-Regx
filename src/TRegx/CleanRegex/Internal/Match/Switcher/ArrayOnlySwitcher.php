<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

class ArrayOnlySwitcher implements Switcher
{
    /** @var array */
    private $switcher;
    /** @var callable */
    private $mapper;

    public function __construct(Switcher $switcher, callable $mapper)
    {
        $this->switcher = $switcher;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        $mapper = $this->mapper;
        return $mapper($this->switcher->all());
    }

    public function first()
    {
        return $this->switcher->first();
    }
}
